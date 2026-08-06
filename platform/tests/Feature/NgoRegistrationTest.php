<?php

namespace Tests\Feature;

use App\Http\Controllers\RegisterController;
use App\Models\Ngo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class NgoRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function completeStepsOneToThree(): void
    {
        // Rendering the form is what starts the clock the timing check reads.
        $this->get('/en/register/step/1')->assertOk();

        $this->post('/en/register/step/1', [
            'name' => 'Community Voices Kosovo',
            'abbreviation' => 'CVK',
            'registration_number' => '5119876543',
            'fiscal_number' => '600123456',
        ])->assertRedirect('/en/register/step/2');

        $this->post('/en/register/step/2', [
            'primary_community' => 'Roma',
            'additional_communities' => ['Roma', 'Ashkali', 'Egyptian'],
            'activity_area' => 'Education',
        ])->assertRedirect('/en/register/step/3');

        $this->post('/en/register/step/3', [
            'responsible_person' => 'Arta Krasniqi',
            'responsible_person_contact' => "+383 44 123 456\ninfo@cvk-example.org",
        ])->assertRedirect('/en/register/step/4');
    }

    /**
     * A real applicant spends minutes on the form; the test suite spends milliseconds.
     */
    protected function submit(array $extra = []): TestResponse
    {
        $this->travel(RegisterController::MIN_SECONDS_TO_COMPLETE + 5)->seconds();

        return $this->post('/en/register', array_merge(['declaration' => '1'], $extra));
    }

    public function test_entry_point_redirects_to_the_first_step(): void
    {
        $this->get('/en/register')->assertRedirect('/en/register/step/1');
    }

    public function test_steps_cannot_be_skipped(): void
    {
        $this->get('/en/register/step/4')->assertRedirect('/en/register/step/1');
    }

    public function test_there_is_no_fifth_step(): void
    {
        $this->get('/en/register/step/5')->assertNotFound();
    }

    public function test_every_field_on_the_form_is_required_except_additional_communities(): void
    {
        $this->post('/en/register/step/1', [])
            ->assertSessionHasErrors(['name', 'abbreviation', 'registration_number', 'fiscal_number']);

        $this->post('/en/register/step/1', [
            'name' => 'Community Voices Kosovo',
            'abbreviation' => 'CVK',
            'registration_number' => '5119876543',
            'fiscal_number' => '600123456',
        ]);

        $this->post('/en/register/step/2', [])
            ->assertSessionHasErrors(['primary_community', 'activity_area'])
            ->assertSessionDoesntHaveErrors('additional_communities');

        $this->post('/en/register/step/2', [
            'primary_community' => 'Roma',
            'activity_area' => 'Education',
        ])->assertRedirect('/en/register/step/3');

        $this->post('/en/register/step/3', [])
            ->assertSessionHasErrors(['responsible_person', 'responsible_person_contact']);
    }

    public function test_a_completed_step_can_be_revisited(): void
    {
        $this->post('/en/register/step/1', [
            'name' => 'Test Org',
            'abbreviation' => 'TO',
            'registration_number' => '5111111111',
            'fiscal_number' => '600000000',
        ])->assertRedirect('/en/register/step/2');

        $this->get('/en/register/step/1')
            ->assertOk()
            ->assertSee('Test Org');
    }

    public function test_going_back_keeps_input_without_requiring_a_valid_step(): void
    {
        $this->post('/en/register/step/1', [
            'name' => 'Test Org',
            'abbreviation' => 'TO',
            'registration_number' => '5111111111',
            'fiscal_number' => '600000000',
        ]);

        $this->post('/en/register/step/2', [
            'action' => 'back',
            'primary_community' => 'Serb',
        ])->assertRedirect('/en/register/step/1')->assertSessionHasNoErrors();

        $this->get('/en/register/step/2')->assertOk()->assertSee('Serb');
    }

    public function test_a_duplicate_registration_number_is_rejected(): void
    {
        Ngo::create([
            'name' => 'Existing',
            'slug' => 'existing',
            'registration_number' => '5119876543',
        ]);

        $this->post('/en/register/step/1', [
            'name' => 'Community Voices Kosovo',
            'abbreviation' => 'CVK',
            'registration_number' => '5119876543',
            'fiscal_number' => '600123456',
        ])->assertSessionHasErrors('registration_number');
    }

    public function test_a_closed_case_gives_the_applicant_a_way_forward_not_just_taken(): void
    {
        $closed = Ngo::create([
            'name' => 'Previously Rejected',
            'slug' => 'previously-rejected',
            'reference_number' => 'NGO-2026-OLD123',
            'registration_number' => '5119876543',
            'status' => 'rejected',
            'is_active' => false,
            'submitted_at' => now(),
        ]);

        $this->get('/en/register/step/1')->assertOk();

        $response = $this->post('/en/register/step/1', [
            'name' => 'Community Voices Kosovo',
            'abbreviation' => 'CVK',
            'registration_number' => '5119876543',
            'fiscal_number' => '600123456',
        ]);

        $response->assertSessionHasErrors('registration_number');

        $message = session('errors')->first('registration_number');

        // The reference number is the only thread they have back to the closed case.
        $this->assertStringContainsString($closed->reference_number, $message);
        $this->assertStringNotContainsString('already been taken', $message);
    }

    public function test_an_active_registration_number_still_reports_a_plain_clash(): void
    {
        Ngo::create([
            'name' => 'Existing',
            'slug' => 'existing-active',
            'registration_number' => '5119876500',
            'status' => 'approved',
            'is_active' => true,
        ]);

        $this->get('/en/register/step/1')->assertOk();

        $this->post('/en/register/step/1', [
            'name' => 'Community Voices Kosovo',
            'abbreviation' => 'CVK',
            'registration_number' => '5119876500',
            'fiscal_number' => '600123456',
        ])->assertSessionHasErrors('registration_number');

        $this->assertSame(
            __('ui.registration_number_taken'),
            session('errors')->first('registration_number')
        );
    }

    public function test_an_applicant_can_track_their_application_without_seeing_internal_notes(): void
    {
        $this->completeStepsOneToThree();
        $this->submit();

        $ngo = Ngo::first();

        $this->get('/en/register/track?reference='.$ngo->reference_number)
            ->assertOk()
            ->assertSee($ngo->reference_number)
            ->assertSee(__('ui.status_pending'));

        $ngo->reject('Internal: fiscal number looks fabricated.');

        $response = $this->get('/en/register/track?reference='.$ngo->reference_number);

        $response->assertOk()
            ->assertSee(__('ui.status_closed'))
            ->assertDontSee('fabricated');
    }

    public function test_tracking_an_unknown_reference_says_so(): void
    {
        $this->get('/en/register/track?reference=NGO-2026-NOPE00')
            ->assertOk()
            ->assertSee('No application found', false);
    }

    public function test_the_submission_itself_opens_the_decision_history(): void
    {
        $this->completeStepsOneToThree();
        $this->submit();

        $history = Ngo::first()->statusHistories;

        $this->assertCount(1, $history);
        $this->assertSame('submitted', $history->first()->event);
        $this->assertNull($history->first()->old_status);
        $this->assertNull($history->first()->changed_by);
    }

    public function test_every_step_renders_in_both_locales(): void
    {
        $this->completeStepsOneToThree();

        foreach (['en', 'sq'] as $locale) {
            foreach (range(1, 4) as $step) {
                $this->get("/{$locale}/register/step/{$step}")->assertOk();
            }
        }
    }

    public function test_the_full_wizard_creates_a_pending_ngo(): void
    {
        $this->completeStepsOneToThree();

        $this->get('/en/register/step/4')
            ->assertOk()
            ->assertSee('Community Voices Kosovo')
            ->assertSee('Arta Krasniqi')
            ->assertSee('5119876543');

        $this->submit()->assertRedirect('/en/register/complete');

        $ngo = Ngo::first();

        $this->assertNotNull($ngo);
        $this->assertSame('Community Voices Kosovo', $ngo->name);
        $this->assertSame('CVK', $ngo->abbreviation);
        $this->assertSame('5119876543', $ngo->registration_number);
        $this->assertSame('600123456', $ngo->fiscal_number);
        $this->assertSame('Arta Krasniqi', $ngo->responsible_person);
        $this->assertSame("+383 44 123 456\ninfo@cvk-example.org", $ngo->responsible_person_contact);
        $this->assertSame('Roma', $ngo->primary_community);
        $this->assertSame('Education', $ngo->category);
        $this->assertSame('pending', $ngo->status);
        $this->assertFalse($ngo->is_active);
        $this->assertNotNull($ngo->submitted_at);
        $this->assertNotNull($ngo->declared_at);

        // A verbatim copy of the application is kept as the reviewer's evidence.
        $this->assertSame('5119876543', $ngo->submitted_data['registration_number']);
        $this->assertSame('Arta Krasniqi', $ngo->submitted_data['responsible_person']);
        $this->assertSame('Education', $ngo->submitted_data['activity_area']);
        $this->assertStringStartsWith('NGO-', $ngo->reference_number);

        // The primary community is not repeated in the additional list.
        $this->assertSame(['Ashkali', 'Egyptian'], $ngo->additional_communities);

        $this->get('/en/register/complete')
            ->assertOk()
            ->assertSee($ngo->reference_number);
    }

    public function test_the_declaration_must_be_confirmed(): void
    {
        $this->completeStepsOneToThree();

        $this->travel(RegisterController::MIN_SECONDS_TO_COMPLETE + 5)->seconds();
        $this->post('/en/register', [])->assertSessionHasErrors('declaration');

        $this->assertSame(0, Ngo::count());
    }

    public function test_the_privacy_notice_is_shown_before_contact_details_are_entered(): void
    {
        $this->completeStepsOneToThree();

        $this->get('/en/register/step/3')
            ->assertOk()
            ->assertSee(__('ui.privacy_notice_title'))
            ->assertSee('not published', false);
    }

    public function test_a_filled_honeypot_blocks_the_submission(): void
    {
        $this->completeStepsOneToThree();

        $this->submit([RegisterController::HONEYPOT_FIELD => 'http://spam.example'])
            ->assertRedirect('/en/register/step/4')
            ->assertSessionHasErrors('submission');

        $this->assertSame(0, Ngo::count());
    }

    public function test_a_filled_honeypot_blocks_an_intermediate_step(): void
    {
        $this->get('/en/register/step/1')->assertOk();

        $this->post('/en/register/step/1', [
            'name' => 'Spam Org',
            'abbreviation' => 'SO',
            'registration_number' => '5100000000',
            'fiscal_number' => '600000000',
            RegisterController::HONEYPOT_FIELD => 'http://spam.example',
        ])->assertRedirect('/en/register/step/1')->assertSessionHasErrors('submission');

        // Nothing was stored, so the applicant never advanced.
        $this->get('/en/register/step/2')->assertRedirect('/en/register/step/1');
    }

    public function test_a_submission_completed_too_fast_is_blocked(): void
    {
        $this->completeStepsOneToThree();

        // No time travel: the whole flow finished in well under the minimum.
        $this->post('/en/register', ['declaration' => '1'])
            ->assertRedirect('/en/register/step/4')
            ->assertSessionHasErrors('submission');

        $this->assertSame(0, Ngo::count());
    }

    public function test_a_submission_with_no_rendered_form_is_blocked(): void
    {
        // Straight to the POSTs, never loading a page — no session clock was ever started.
        $this->post('/en/register/step/1', [
            'name' => 'Bot Org',
            'abbreviation' => 'BO',
            'registration_number' => '5100000001',
            'fiscal_number' => '600000001',
        ]);
        $this->post('/en/register/step/2', [
            'primary_community' => 'Roma',
            'activity_area' => 'Education',
        ]);
        $this->post('/en/register/step/3', [
            'responsible_person' => 'Bot',
            'responsible_person_contact' => 'bot@example.com',
        ]);

        $this->post('/en/register', ['declaration' => '1'])
            ->assertSessionHasErrors('submission');

        $this->assertSame(0, Ngo::count());
    }

    public function test_submitting_before_the_review_step_is_blocked(): void
    {
        $this->post('/en/register/step/1', [
            'name' => 'Test Org',
            'abbreviation' => 'TO',
            'registration_number' => '5111111111',
            'fiscal_number' => '600000000',
        ]);

        $this->submit()->assertRedirect('/en/register/step/2');

        $this->assertSame(0, Ngo::count());
    }

    public function test_a_pending_ngo_is_not_listed_publicly(): void
    {
        $this->completeStepsOneToThree();
        $this->submit();

        $ngo = Ngo::first();

        $this->get('/en/ngos')->assertOk()->assertDontSee('Community Voices Kosovo');
        $this->get('/en/ngos/'.$ngo->slug)->assertNotFound();

        $ngo->update(['status' => 'approved', 'is_active' => true]);

        $this->get('/en/ngos')->assertOk()->assertSee('Community Voices Kosovo');
        $this->get('/en/ngos/'.$ngo->slug)->assertOk();
    }
}
