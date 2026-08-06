<?php

namespace Tests\Feature;

use App\Filament\Resources\Ngos\Pages\EditNgo;
use App\Filament\Resources\Ngos\Pages\ListNgos;
use App\Filament\Resources\Ngos\RelationManagers\StatusHistoriesRelationManager;
use App\Models\Ngo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NgoReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    protected function pendingNgo(array $attributes = []): Ngo
    {
        return Ngo::create(array_merge([
            'reference_number' => 'NGO-2026-ABC123',
            'name' => 'Community Voices Kosovo',
            'abbreviation' => 'CVK',
            'slug' => 'community-voices-kosovo',
            'registration_number' => '5119876543',
            'fiscal_number' => '600123456',
            'responsible_person' => 'Arta Krasniqi',
            'responsible_person_contact' => "+383 44 123 456\ninfo@cvk-example.org",
            'category' => 'Education',
            'primary_community' => 'Roma',
            'additional_communities' => ['Ashkali'],
            'is_active' => false,
            'status' => 'pending',
            'submitted_data' => [
                'name' => 'Community Voices Kosovo',
                'abbreviation' => 'CVK',
                'registration_number' => '5119876543',
                'fiscal_number' => '600123456',
                'primary_community' => 'Roma',
                'additional_communities' => ['Ashkali'],
                'activity_area' => 'Education',
                'responsible_person' => 'Arta Krasniqi',
                'responsible_person_contact' => "+383 44 123 456\ninfo@cvk-example.org",
            ],
            'declared_at' => now(),
            'submitted_at' => now(),
        ], $attributes));
    }

    public function test_the_review_page_shows_the_submitted_application_read_only(): void
    {
        $ngo = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertSuccessful()
            ->assertSee('Submitted application')
            ->assertSee('5119876543')
            ->assertSee('600123456')
            ->assertSee('Arta Krasniqi')
            ->assertSee('Public profile');
    }

    public function test_the_submitted_snapshot_survives_staff_edits_and_flags_them(): void
    {
        $ngo = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->fillForm(['registration_number' => '5100000999'])
            ->call('save')
            ->assertHasNoFormErrors();

        $ngo->refresh();

        // The live record moved; the evidence did not.
        $this->assertSame('5100000999', $ngo->registration_number);
        $this->assertSame('5119876543', $ngo->submitted_data['registration_number']);

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertSee('5119876543')
            ->assertSee('Edited by staff');
    }

    public function test_approving_publishes_in_one_step(): void
    {
        $ngo = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->callAction('approve')
            ->assertHasNoActionErrors();

        $ngo->refresh();

        $this->assertSame('approved', $ngo->status);
        $this->assertTrue($ngo->is_active);
    }

    public function test_rejecting_requires_a_reason_and_records_it(): void
    {
        $ngo = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->callAction('reject', [])
            ->assertHasActionErrors(['reason']);

        $this->assertSame('pending', $ngo->refresh()->status);

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->callAction('reject', ['reason' => 'Registration number does not match the NGO registry.'])
            ->assertHasNoActionErrors();

        $ngo->refresh();

        $this->assertSame('rejected', $ngo->status);
        $this->assertFalse($ngo->is_active);
        $this->assertSame('Registration number does not match the NGO registry.', $ngo->review_notes);
    }

    public function test_unpublishing_keeps_the_application_approved(): void
    {
        $ngo = $this->pendingNgo(['status' => 'approved', 'is_active' => true]);

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->callAction('unpublish')
            ->assertHasNoActionErrors();

        $ngo->refresh();

        $this->assertSame('approved', $ngo->status);
        $this->assertFalse($ngo->is_active);
    }

    public function test_approve_is_hidden_once_published_and_unpublish_is_hidden_while_pending(): void
    {
        $pending = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $pending->getRouteKey()])
            ->assertActionVisible('approve')
            ->assertActionVisible('reject')
            ->assertActionHidden('unpublish');

        $published = $this->pendingNgo([
            'slug' => 'other', 'registration_number' => '5100000000',
            'reference_number' => 'NGO-2026-XYZ999',
            'status' => 'approved', 'is_active' => true,
        ]);

        Livewire::test(EditNgo::class, ['record' => $published->getRouteKey()])
            ->assertActionHidden('approve')
            ->assertActionVisible('unpublish');
    }

    public function test_status_and_visibility_are_not_editable_fields(): void
    {
        $ngo = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertFormFieldDoesNotExist('status')
            ->assertFormFieldDoesNotExist('is_active');
    }

    public function test_saving_the_profile_does_not_change_the_decision(): void
    {
        $ngo = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->fillForm(['contact_email' => 'info@cvk-example.org', 'location' => 'Prizren'])
            ->call('save')
            ->assertHasNoFormErrors();

        $ngo->refresh();

        $this->assertSame('info@cvk-example.org', $ngo->contact_email);
        $this->assertSame('Prizren', $ngo->location);
        $this->assertSame('pending', $ngo->status);
        $this->assertFalse($ngo->is_active);
    }

    public function test_the_list_opens_on_the_pending_queue_when_there_is_work(): void
    {
        $this->pendingNgo();

        Livewire::test(ListNgos::class)
            ->assertSuccessful()
            ->assertSet('activeTab', 'pending');
    }

    public function test_the_list_opens_on_all_when_nothing_is_pending(): void
    {
        $this->pendingNgo(['status' => 'approved', 'is_active' => true]);

        Livewire::test(ListNgos::class)
            ->assertSuccessful()
            ->assertSet('activeTab', 'all');
    }

    public function test_rejecting_closes_the_case_and_locks_the_record(): void
    {
        $ngo = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->callAction('reject', ['reason' => 'Fiscal number does not match the registry.']);

        $ngo->refresh();
        $this->assertTrue($ngo->isClosed());

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertSee('Closed')
            ->assertActionHidden('approve')
            ->assertActionHidden('reject')
            ->assertActionHidden('unpublish')
            ->assertActionVisible('reopen')
            ->assertFormFieldDisabled('contact_email')
            ->assertFormFieldDisabled('slug')
            ->assertFormFieldDisabled('review_notes');
    }

    public function test_reopening_requires_a_justification_and_returns_to_pending(): void
    {
        $ngo = $this->pendingNgo();
        $ngo->approve();
        $ngo->reject('Registry mismatch.');

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->callAction('reopen', [])
            ->assertHasActionErrors(['justification']);

        $this->assertTrue($ngo->refresh()->isClosed());

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->callAction('reopen', ['justification' => 'Applicant supplied the correct registry extract.'])
            ->assertHasNoActionErrors();

        $ngo->refresh();

        // Back to the queue — never restored to its previous approval.
        $this->assertSame('pending', $ngo->status);
        $this->assertFalse($ngo->is_active);
    }

    public function test_an_editor_cannot_reopen_a_closed_case(): void
    {
        $ngo = $this->pendingNgo();
        $ngo->reject('Not eligible.');

        $this->actingAs(User::factory()->create(['role' => 'editor']));

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertActionHidden('reopen');

        $this->assertFalse($ngo->fresh()->canBeReopenedBy(auth()->user()));
    }

    public function test_every_decision_is_kept_in_history_with_its_own_reason(): void
    {
        $ngo = $this->pendingNgo();
        $ngo->recordEvent('submitted', null);

        $ngo->reject('First reason.');
        $ngo->reopen('Reconsidering after new evidence.');
        $ngo->reject('Second reason.');

        $history = $ngo->statusHistories()->orderBy('created_at')->orderBy('id')->get();

        $this->assertSame(
            ['submitted', 'rejected', 'reopened', 'rejected'],
            $history->pluck('event')->all()
        );

        // The first rejection reason survives the second one — the bug a single notes
        // field could never avoid.
        $this->assertSame('First reason.', $history[1]->note);
        $this->assertSame('Reconsidering after new evidence.', $history[2]->note);
        $this->assertSame('Second reason.', $history[3]->note);

        $this->assertSame('rejected', $history[2]->old_status);
        $this->assertSame('pending', $history[2]->new_status);
    }

    public function test_visibility_changes_are_recorded_without_changing_the_status(): void
    {
        $ngo = $this->pendingNgo();
        $ngo->approve();
        $ngo->unpublish();

        $ngo->refresh();

        $this->assertSame('approved', $ngo->status);
        $this->assertFalse($ngo->is_active);

        $latest = $ngo->statusHistories()->first();
        $this->assertSame('unpublished', $latest->event);
        $this->assertSame('approved', $latest->new_status);
    }

    public function test_the_decision_history_renders_on_the_review_page(): void
    {
        $ngo = $this->pendingNgo();
        $ngo->recordEvent('submitted', null);
        $ngo->reject('Fiscal number does not match the registry.');

        Livewire::test(StatusHistoriesRelationManager::class, [
            'ownerRecord' => $ngo->refresh(),
            'pageClass' => EditNgo::class,
        ])
            ->assertSuccessful()
            ->assertSee('Fiscal number does not match the registry.')
            ->assertSee('Rejected')
            ->assertSee('Submitted');
    }

    public function test_approving_commits_unsaved_profile_edits_first(): void
    {
        $ngo = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->fillForm(['contact_email' => 'info@cvk-example.org', 'location' => 'Prizren'])
            ->callAction('approve')
            ->assertHasNoActionErrors();

        $ngo->refresh();

        // Publishing what is on screen, not a stale copy of it.
        $this->assertSame('info@cvk-example.org', $ngo->contact_email);
        $this->assertSame('Prizren', $ngo->location);
        $this->assertTrue($ngo->is_active);
    }

    public function test_the_readiness_callout_reflects_a_complete_profile(): void
    {
        $ngo = $this->pendingNgo();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertSee('The public profile is still incomplete.');

        $ngo->setTranslation('name', 'sq', 'Zërat e Komunitetit');
        $ngo->setTranslation('description', 'en', 'We support access to education.');
        $ngo->contact_email = 'info@cvk-example.org';
        $ngo->location = 'Prizren';
        $ngo->save();

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertSee('The public profile is complete.')
            ->assertDontSee('still incomplete');
    }

    public function test_a_public_registration_is_never_attributed_to_a_logged_in_staff_user(): void
    {
        // A reviewer is signed in to the panel in this browser session.
        $this->assertNotNull(auth()->id());

        $ngo = $this->pendingNgo();
        $ngo->recordSubmission();

        $this->assertNull($ngo->statusHistories()->first()->changed_by);
    }

    public function test_the_missing_pieces_warning_lists_what_the_profile_still_needs(): void
    {
        $ngo = $this->pendingNgo();

        $missing = $ngo->missingForPublication();

        $this->assertContains('Albanian translation of the name', $missing);
        $this->assertContains('Public contact e-mail or phone', $missing);
        $this->assertContains('Location', $missing);

        $ngo->setTranslation('name', 'sq', 'Zërat e Komunitetit');
        $ngo->setTranslation('description', 'en', 'We support access to education.');
        $ngo->contact_email = 'info@cvk-example.org';
        $ngo->location = 'Prizren';
        $ngo->save();

        $this->assertSame([], $ngo->refresh()->missingForPublication());
    }
}
