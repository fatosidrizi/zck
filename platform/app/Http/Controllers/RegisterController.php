<?php

namespace App\Http\Controllers;

use App\Models\Ngo;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public const SESSION_KEY = 'ngo_registration';

    public const COMMUNITIES = [
        'Ashkali', 'Bosniak', 'Egyptian', 'Goran', 'Croat', 'Montenegrin', 'Roma', 'Serb', 'Turkish',
    ];

    public const ACTIVITY_AREAS = [
        'Human Rights', 'Anti-Discrimination', 'Anti-Bullying', 'Education', 'Health', 'Culture',
        'Youth', 'Environment', 'Economic Development', 'Legal Aid', 'Media', 'Other',
    ];

    public const STEPS = [
        1 => 'identity',
        2 => 'community',
        3 => 'contact',
        4 => 'review',
    ];

    /**
     * A decoy input hidden from people but not from naive bots. Named after a field the
     * form does not actually collect, so browser autofill has no reason to touch it.
     */
    public const HONEYPOT_FIELD = 'organization_reference';

    /**
     * Nobody reads and fills four steps faster than this.
     */
    public const MIN_SECONDS_TO_COMPLETE = 10;

    /**
     * Entry point — always resumes at the furthest step the applicant has unlocked.
     */
    public function create()
    {
        return redirect()->route('register.step', ['step' => $this->currentStep()]);
    }

    public function show(string $locale, int $step)
    {
        if (! isset(self::STEPS[$step])) {
            abort(404);
        }

        // Steps are unlocked in order; jumping ahead lands on the furthest unlocked step.
        if ($step > $this->currentStep()) {
            return redirect()->route('register.step', ['step' => $this->currentStep()]);
        }

        if (! session()->has(self::SESSION_KEY.'.started_at')) {
            session()->put(self::SESSION_KEY.'.started_at', now()->timestamp);
        }

        return view('pages.register.step-'.self::STEPS[$step], [
            'step' => $step,
            'furthestStep' => $this->currentStep(),
            'data' => $this->data(),
            'communities' => self::COMMUNITIES,
            'activityAreas' => self::ACTIVITY_AREAS,
        ]);
    }

    /**
     * Validate one step, merge it into the session draft, and move on.
     */
    public function update(Request $request, string $locale, int $step)
    {
        if (! isset(self::STEPS[$step]) || $step === array_key_last(self::STEPS)) {
            abort(404);
        }

        if ($step > $this->currentStep()) {
            return redirect()->route('register.step', ['step' => $this->currentStep()]);
        }

        if ($rejection = $this->rejectBot($request, $step)) {
            return $rejection;
        }

        // Going back must never be blocked by an incomplete step — keep what was typed
        // and let the per-step rules run again on the way forward.
        if ($request->input('action') === 'back') {
            $this->putData($request->only($this->fieldsFor($step)));

            return redirect()->route('register.step', ['step' => max(1, $step - 1)]);
        }

        $validated = $request->validate($this->rules($step), [], $this->attributes());

        if ($step === 2) {
            // The primary community is implied; keep it out of the additional list.
            $validated['additional_communities'] = array_values(array_diff(
                $validated['additional_communities'] ?? [],
                [$validated['primary_community']]
            ));
        }

        $this->putData($validated);

        session()->put(self::SESSION_KEY.'.furthest', max($this->currentStep(), $step + 1));

        return redirect()->route('register.step', ['step' => $step + 1]);
    }

    /**
     * Final submit from the review step.
     */
    public function store(Request $request)
    {
        $lastStep = array_key_last(self::STEPS);

        if ($this->currentStep() < $lastStep) {
            return redirect()->route('register.step', ['step' => $this->currentStep()]);
        }

        if ($rejection = $this->rejectBot($request, $lastStep)) {
            return $rejection;
        }

        $request->validate(
            ['declaration' => 'accepted'],
            ['declaration.accepted' => __('ui.declaration_required')]
        );

        // Re-run every step's rules against the collected draft: the draft may have gone
        // stale (e.g. another NGO claimed the same registration number) while it sat in session.
        $rules = array_merge($this->rules(1), $this->rules(2), $this->rules(3));

        $validator = validator($this->data(), $rules, [], $this->attributes());

        if ($validator->fails()) {
            $field = array_key_first($validator->errors()->messages());

            return redirect()
                ->route('register.step', ['step' => $this->stepForField($field)])
                ->withErrors($validator);
        }

        $data = $validator->validated();

        $ngo = Ngo::create([
            'reference_number' => $this->generateReferenceNumber(),
            'name' => $data['name'],
            'abbreviation' => $data['abbreviation'],
            'slug' => $this->generateSlug($data['name']),
            'registration_number' => $data['registration_number'],
            'fiscal_number' => $data['fiscal_number'],
            'responsible_person' => $data['responsible_person'],
            'responsible_person_contact' => $data['responsible_person_contact'],
            'category' => $data['activity_area'],
            'primary_community' => $data['primary_community'],
            'additional_communities' => $data['additional_communities'] ?? [],
            'is_active' => false,
            'status' => 'pending',
            // Keep the application verbatim; the columns above are the live record and
            // staff may correct them during review.
            'submitted_data' => $data,
            'declared_at' => now(),
            'submitted_at' => now(),
        ]);

        // The trail starts with the applicant, not with the first reviewer to touch it.
        $ngo->recordSubmission();

        session()->forget(self::SESSION_KEY);

        return redirect()
            ->route('register.complete')
            ->with('reference_number', $ngo->reference_number);
    }

    /**
     * Lets an applicant look up their own application. Deliberately exposes only the
     * outcome — review notes and rejection reasons are internal.
     */
    public function track(Request $request)
    {
        $ngo = null;

        if ($reference = trim((string) $request->input('reference'))) {
            $ngo = Ngo::where('reference_number', Str::upper($reference))
                ->whereNotNull('submitted_at')
                ->first();
        }

        return view('pages.register.track', compact('ngo'));
    }

    public function complete()
    {
        if (! session()->has('reference_number')) {
            return redirect()->route('register');
        }

        return view('pages.register.complete', [
            'referenceNumber' => session('reference_number'),
        ]);
    }

    /**
     * Two cheap, invisible bot checks: a decoy field nobody can see, and a floor on how
     * fast the whole form can be completed. Neither costs a real applicant anything.
     *
     * A tripped check returns a generic error rather than silently discarding the
     * submission, so a false positive never loses someone's work without telling them.
     */
    protected function rejectBot(Request $request, int $step): ?RedirectResponse
    {
        $reason = match (true) {
            filled($request->input(self::HONEYPOT_FIELD)) => 'honeypot',
            $step === array_key_last(self::STEPS) && $this->secondsSinceStart() < self::MIN_SECONDS_TO_COMPLETE => 'too_fast',
            default => null,
        };

        if ($reason === null) {
            return null;
        }

        Log::warning('NGO registration blocked as automated', [
            'reason' => $reason,
            'step' => $step,
            'ip' => $request->ip(),
        ]);

        return redirect()
            ->route('register.step', ['step' => $step])
            ->withErrors(['submission' => __('ui.submission_blocked')]);
    }

    protected function secondsSinceStart(): int
    {
        $startedAt = session()->get(self::SESSION_KEY.'.started_at');

        // No recorded start means the form was never rendered — that is a bot, not a person.
        return $startedAt === null ? 0 : now()->timestamp - (int) $startedAt;
    }

    /**
     * Validation rules per step.
     */
    protected function rules(int $step): array
    {
        return match ($step) {
            1 => [
                'name' => 'required|string|max:255',
                'abbreviation' => 'required|string|max:50',
                'registration_number' => ['required', 'string', 'max:100', $this->registrationNumberIsFree()],
                'fiscal_number' => 'required|string|max:100',
            ],
            2 => [
                'primary_community' => ['required', Rule::in(self::COMMUNITIES)],
                'additional_communities' => 'nullable|array',
                'additional_communities.*' => [Rule::in(self::COMMUNITIES)],
                'activity_area' => ['required', Rule::in(self::ACTIVITY_AREAS)],
            ],
            3 => [
                'responsible_person' => 'required|string|max:255',
                'responsible_person_contact' => 'required|string|max:500',
            ],
            default => [],
        };
    }

    /**
     * "Already taken" is a dead end when the record holding the number is a closed case:
     * the applicant cannot see it, cannot reach it, and has nothing to act on. Point them
     * at the reference number and a human instead.
     */
    protected function registrationNumberIsFree(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) {
            $existing = Ngo::where('registration_number', $value)->first();

            if ($existing === null) {
                return;
            }

            $fail($existing->isClosed()
                ? __('ui.registration_number_closed', ['reference' => $existing->reference_number])
                : __('ui.registration_number_taken'));
        };
    }

    /**
     * Which step owns a field — used to send a stale draft back to the right place.
     */
    protected function stepForField(string $field): int
    {
        $field = Str::before($field, '.');

        foreach (array_keys(self::STEPS) as $step) {
            if (in_array($field, $this->fieldsFor($step), true)) {
                return $step;
            }
        }

        return 1;
    }

    /**
     * Plain field names a step owns.
     */
    protected function fieldsFor(int $step): array
    {
        return array_values(array_filter(
            array_keys($this->rules($step)),
            fn ($key) => ! str_contains($key, '.')
        ));
    }

    protected function attributes(): array
    {
        return [
            'name' => __('ui.org_name'),
            'abbreviation' => __('ui.abbreviation'),
            'registration_number' => __('ui.registration_number'),
            'fiscal_number' => __('ui.fiscal_number'),
            'primary_community' => __('ui.primary_community'),
            'additional_communities' => __('ui.additional_communities'),
            'activity_area' => __('ui.activity_area'),
            'responsible_person' => __('ui.responsible_person'),
            'responsible_person_contact' => __('ui.responsible_person_contact'),
        ];
    }

    protected function data(): array
    {
        return session()->get(self::SESSION_KEY.'.data', []);
    }

    protected function putData(array $values): void
    {
        session()->put(self::SESSION_KEY.'.data', array_merge($this->data(), $values));
    }

    protected function currentStep(): int
    {
        return min(
            array_key_last(self::STEPS),
            max(1, (int) session()->get(self::SESSION_KEY.'.furthest', 1))
        );
    }

    protected function generateSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'ngo';
        $slug = $base;

        while (Ngo::where('slug', $slug)->exists()) {
            $slug = $base.'-'.Str::lower(Str::random(4));
        }

        return $slug;
    }

    protected function generateReferenceNumber(): string
    {
        do {
            $reference = 'NGO-'.now()->year.'-'.Str::upper(Str::random(6));
        } while (Ngo::where('reference_number', $reference)->exists());

        return $reference;
    }
}
