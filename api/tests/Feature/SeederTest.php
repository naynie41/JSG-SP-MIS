<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Models\BenefitFlag;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Notification\Models\Notification;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Gis\GeoBoundary;
use App\Domain\Reporting\Models\DashboardSnapshot;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_produces_expected_baseline(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Seven predefined roles, each present.
        $this->assertCount(7, Role::all());
        foreach (RoleKey::cases() as $roleKey) {
            $this->assertDatabaseHas('roles', ['key' => $roleKey->value, 'is_system' => true]);
        }

        // MFA is mandatory for the privileged roles.
        $this->assertTrue(Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()->requires_mfa);
        $this->assertTrue(Role::where('key', RoleKey::Executive->value)->firstOrFail()->requires_mfa);

        // All registered permissions are synced and System Administrator holds them all.
        $this->assertGreaterThanOrEqual(13, Permission::count());
        $admin = Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail();
        $this->assertSame(Permission::count(), $admin->permissions()->count());

        // Sample MDAs.
        $this->assertDatabaseHas('mdas', ['name' => 'Ministry of Health']);
        $this->assertDatabaseHas('mdas', ['name' => 'Ministry of Women Affairs & Social Development']);
        $this->assertSame(2, Mda::count());

        // One seeded System Administrator account, MFA not yet enrolled.
        $superuser = User::where('email', 'admin@spmis.local')->firstOrFail();
        $this->assertSame(RoleKey::SystemAdministrator->value, $superuser->role?->key);
        $this->assertFalse($superuser->mfa_enabled);
        $this->assertTrue($superuser->mfaRequired());

        // Sample beneficiaries spread across both sample MDAs (cross-MDA test data).
        $this->assertSame(6, Beneficiary::count());
        $this->assertSame(2, Beneficiary::query()->distinct()->count('owner_mda_id'));

        // Phase 4 sample data: individual + household programmes, enrolments, a
        // spread of benefits across both MDAs, and a cross-MDA double-dipping flag.
        $this->assertGreaterThanOrEqual(3, Programme::count());
        $this->assertTrue(Programme::query()->where('type', 'household')->exists());
        $this->assertTrue(Programme::query()->where('type', 'individual')->exists());
        $this->assertTrue(Enrollment::query()->whereNotNull('household_id')->exists());
        $this->assertSame(2, Benefit::query()->distinct()->count('mda_id')); // cross-MDA deliveries
        $this->assertGreaterThanOrEqual(1, BenefitFlag::count()); // double-dipping flagged, not blocked
    }

    public function test_seeder_produces_phase_5_coordination_sample_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Referrals across both MDAs and both directions, including a rejected one
        // (reason required) and an overdue/escalated one (FR-REF-01/02/04/05).
        $referrals = Referral::query()->withoutGlobalScopes();
        $this->assertGreaterThanOrEqual(6, (clone $referrals)->count());
        $this->assertSame(2, (clone $referrals)->distinct()->count('from_mda_id')); // both MDAs originate
        $this->assertTrue((clone $referrals)->where('status', 'rejected')->whereNotNull('reason')->exists());
        $this->assertTrue((clone $referrals)->whereNotNull('sla_breached_at')->where('escalation_level', '>', 0)->exists());

        // Grievances across categories/channels, including an escalated one and a
        // general grievance with no beneficiary link (FR-GRM-01/02/03).
        $grievances = Grievance::query()->withoutGlobalScopes();
        $this->assertGreaterThanOrEqual(5, (clone $grievances)->count());
        $this->assertGreaterThanOrEqual(4, (clone $grievances)->distinct()->count('category'));
        $this->assertTrue((clone $grievances)->whereNull('beneficiary_id')->exists());
        $this->assertTrue((clone $grievances)->whereNotNull('sla_breached_at')->where('escalation_level', '>', 0)->exists());

        // Synthetic notifications, including an unread one and a referral deep-link.
        $notifications = Notification::query()->withoutGlobalScopes();
        $this->assertGreaterThanOrEqual(4, (clone $notifications)->count());
        $this->assertTrue((clone $notifications)->whereNull('read_at')->exists());
        $this->assertTrue((clone $notifications)->where('related_type', 'referral')->exists());
    }

    public function test_seeder_produces_phase_6_reporting_sample_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // A Development Partner funding programmes → the partner dashboard renders (FR-RPT-02).
        $partner = User::where('email', 'partner@spmis.local')->firstOrFail();
        $this->assertSame(RoleKey::DevelopmentPartner->value, $partner->role?->key);
        $this->assertGreaterThanOrEqual(1, ProgrammeFunder::query()->where('user_id', $partner->id)->count());

        // LGA boundaries loaded → the GIS choropleth renders (FR-GIS-01).
        $this->assertGreaterThanOrEqual(2, GeoBoundary::query()->where('level', 'lga')->count());

        // Dashboard snapshots warmed → dashboards serve from the summary layer (FR-RPT-01).
        $this->assertTrue(DashboardSnapshot::query()->where('scope_key', 'state_wide')->exists());
    }
}
