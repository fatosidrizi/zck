<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Filament\Resources\DiscriminationReports\DiscriminationReportResource;
use App\Filament\Resources\News\NewsResource;
use App\Filament\Resources\Ngos\NgoResource;
use App\Filament\Resources\Ngos\Pages\EditNgo;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\LatestReports;
use App\Models\DiscriminationReport;
use App\Models\News;
use App\Models\Ngo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function user(UserRole $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    protected function pendingNgo(): Ngo
    {
        return Ngo::create([
            'name' => 'Community Voices Kosovo',
            'slug' => 'community-voices-kosovo',
            'category' => 'Education',
            'is_active' => false,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);
    }

    protected function report(): DiscriminationReport
    {
        return DiscriminationReport::create([
            'reporter_name' => 'Anonymous',
            'type' => 'employment',
            'description' => 'Test report',
            'status' => 'new',
        ]);
    }

    public function test_only_staff_can_access_the_admin_panel(): void
    {
        $this->assertFalse($this->user(UserRole::User)->canAccessPanel(filament()->getPanel('admin')));
        $this->assertTrue($this->user(UserRole::Editor)->canAccessPanel(filament()->getPanel('admin')));

        $this->actingAs($this->user(UserRole::User))->get('/admin')->assertForbidden();
        $this->actingAs($this->user(UserRole::Editor))->get('/admin')->assertOk();
    }

    public function test_public_signup_always_creates_a_plain_user(): void
    {
        $this->post('/en/signup', [
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'super_admin',
        ])->assertRedirect();

        $this->assertSame(UserRole::User, User::where('email', 'visitor@example.com')->firstOrFail()->role);
    }

    public function test_every_staff_role_can_write_content_but_only_admins_can_delete_it(): void
    {
        $news = News::create([
            'title' => 'Hello',
            'slug' => 'hello',
            'body' => 'Body',
            'category' => 'news',
            'status' => 'draft',
            'author_id' => $this->user(UserRole::SuperAdmin)->id,
        ]);

        foreach ([UserRole::Editor, UserRole::Admin, UserRole::SuperAdmin] as $role) {
            $user = $this->user($role);
            $this->assertTrue($user->can('create', News::class), "$role->value should create");
            $this->assertTrue($user->can('update', $news), "$role->value should update");
        }

        $this->assertFalse($this->user(UserRole::Editor)->can('delete', $news));
        $this->assertTrue($this->user(UserRole::Admin)->can('delete', $news));
        $this->assertTrue($this->user(UserRole::SuperAdmin)->can('delete', $news));

        $this->actingAs($this->user(UserRole::Editor));
        $this->assertTrue(NewsResource::canEdit($news));
        $this->assertFalse(NewsResource::canDelete($news));
        $this->assertFalse(NewsResource::canDeleteAny());
    }

    public function test_editors_maintain_ngo_profiles_but_cannot_decide_applications(): void
    {
        $ngo = $this->pendingNgo();

        $this->actingAs($this->user(UserRole::Editor));

        $this->assertTrue(NgoResource::canEdit($ngo));
        $this->assertFalse(auth()->user()->can('review', $ngo));

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertActionHidden('approve')
            ->assertActionHidden('reject')
            ->assertActionHidden('delete');

        $this->assertTrue($ngo->fresh()->isPending());
    }

    public function test_admins_decide_ngo_applications(): void
    {
        $ngo = $this->pendingNgo();

        $this->actingAs($this->user(UserRole::Admin));

        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertActionVisible('approve')
            ->assertActionVisible('reject')
            ->callAction('approve')
            ->assertHasNoErrors();

        $this->assertTrue($ngo->fresh()->isApproved());
    }

    public function test_only_admins_can_reopen_a_rejected_application(): void
    {
        $ngo = $this->pendingNgo();
        $ngo->reject('Incomplete');

        $this->assertTrue($ngo->fresh()->canBeReopenedBy($this->user(UserRole::SuperAdmin)));
        $this->assertTrue($ngo->fresh()->canBeReopenedBy($this->user(UserRole::Admin)));
        $this->assertFalse($ngo->fresh()->canBeReopenedBy($this->user(UserRole::Editor)));
        $this->assertFalse($ngo->fresh()->canBeReopenedBy(null));

        $this->actingAs($this->user(UserRole::Editor));
        Livewire::test(EditNgo::class, ['record' => $ngo->getRouteKey()])
            ->assertActionHidden('reopen');
    }

    public function test_discrimination_reports_are_hidden_from_editors(): void
    {
        $report = $this->report();

        $this->actingAs($this->user(UserRole::Editor));
        $this->assertFalse(DiscriminationReportResource::canViewAny());
        $this->assertFalse(DiscriminationReportResource::canEdit($report));
        $this->assertFalse(LatestReports::canView());
        $this->get(DiscriminationReportResource::getUrl('index'))->assertForbidden();
        $this->get(DiscriminationReportResource::getUrl('edit', ['record' => $report]))->assertForbidden();

        $this->actingAs($this->user(UserRole::Admin));
        $this->assertTrue(DiscriminationReportResource::canViewAny());
        $this->assertTrue(DiscriminationReportResource::canEdit($report));
        $this->assertTrue(LatestReports::canView());
        $this->assertFalse(DiscriminationReportResource::canDelete($report));
        $this->get(DiscriminationReportResource::getUrl('index'))->assertOk();

        $this->actingAs($this->user(UserRole::SuperAdmin));
        $this->assertTrue(DiscriminationReportResource::canDelete($report));
    }

    public function test_reports_can_only_be_assigned_to_people_who_can_open_them(): void
    {
        $editor = $this->user(UserRole::Editor);
        $admin = $this->user(UserRole::Admin);
        $superAdmin = $this->user(UserRole::SuperAdmin);
        $this->user(UserRole::User);

        $handlers = User::reportHandlers()->pluck('id')->all();

        $this->assertEqualsCanonicalizing([$admin->id, $superAdmin->id], $handlers);
        $this->assertNotContains($editor->id, $handlers);
    }

    public function test_only_super_admins_manage_accounts(): void
    {
        $someone = $this->user(UserRole::Editor);

        foreach ([UserRole::Editor, UserRole::Admin] as $role) {
            $this->actingAs($this->user($role));
            $this->assertFalse(UserResource::canViewAny(), "$role->value must not list users");
            $this->assertFalse(UserResource::canCreate(), "$role->value must not create users");
            $this->assertFalse(UserResource::canEdit($someone), "$role->value must not edit users");
            $this->get(UserResource::getUrl('index'))->assertForbidden();
        }

        $this->actingAs($this->user(UserRole::SuperAdmin));
        $this->assertTrue(UserResource::canViewAny());
        $this->assertTrue(UserResource::canCreate());
        $this->assertTrue(UserResource::canEdit($someone));
        $this->assertTrue(UserResource::canDelete($someone));
        $this->get(UserResource::getUrl('index'))->assertOk();
    }

    public function test_a_super_admin_cannot_delete_or_demote_their_own_account(): void
    {
        $me = $this->user(UserRole::SuperAdmin);
        $this->actingAs($me);

        $this->assertFalse(UserResource::canDelete($me));

        Livewire::test(EditUser::class, ['record' => $me->getRouteKey()])
            ->assertActionHidden('delete')
            ->fillForm(['name' => 'Renamed', 'role' => UserRole::Editor->value])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(UserRole::SuperAdmin, $me->fresh()->role);
        $this->assertSame('Renamed', $me->fresh()->name);
    }

    public function test_editing_a_user_without_a_password_keeps_the_existing_one(): void
    {
        $this->actingAs($this->user(UserRole::SuperAdmin));
        $target = $this->user(UserRole::Editor);
        $hash = $target->password;

        Livewire::test(EditUser::class, ['record' => $target->getRouteKey()])
            ->fillForm(['role' => UserRole::Admin->value, 'password' => ''])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(UserRole::Admin, $target->fresh()->role);
        $this->assertSame($hash, $target->fresh()->password);
    }
}
