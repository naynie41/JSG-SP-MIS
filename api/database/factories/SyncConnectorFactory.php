<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Models\SyncConnector;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SyncConnector>
 */
class SyncConnectorFactory extends Factory
{
    protected $model = SyncConnector::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'SOCU feed',
            'source' => RegistrationSource::Socu,
            'owner_mda_id' => Mda::factory(),
            'conflict_policy' => ConflictPolicy::FlagForReview,
            'credentials_ref' => 'socu',
            'enabled' => true,
            'schedule' => 'hourly',
            /*
             * Unbound by default, which is also the state a real connector starts in:
             * the activity is a standing decision an administrator makes.
             *
             * Deliberately NOT defaulted to a fabricated activity. Doing that made the
             * factory create a programme, an activity and a user behind every connector,
             * and those turned up in unrelated tests' programme and activity counts — a
             * fixture that quietly invents catalogue entries is worse than one a test has
             * to finish. Use {@see bound()} where the connector needs to actually run.
             */
            'activity_id' => null,
        ];
    }

    /**
     * A connector that can actually sync: bound to an active activity in its own MDA,
     * created by an officer of that MDA. The engine enrols as that creator, so an
     * activity without one would hold the run.
     */
    public function bound(?Activity $activity = null): static
    {
        /*
         * Applied AFTER creation, not as a state.
         *
         * A state closure sees `owner_mda_id` as whatever the definition left there —
         * an unresolved `MdaFactory` when the caller did not pass one — and states run
         * before those overrides are merged. By `afterCreating` the row exists and the
         * column holds a real id, whichever way it was supplied.
         */
        return $this->afterCreating(function (SyncConnector $connector) use ($activity): void {
            if ($activity !== null) {
                $connector->forceFill(['activity_id' => $activity->id])->save();

                return;
            }

            $mda = Mda::query()->findOrFail($connector->owner_mda_id);
            $creator = User::factory()->create([
                'mda_id' => $mda->id,
                'role_id' => Role::query()->where('key', RoleKey::MdaAdmin->value)->value('id')
                    ?? Role::factory()->create(['key' => RoleKey::MdaAdmin->value])->id,
            ]);

            $connector->forceFill([
                'activity_id' => Activity::factory()
                    ->forProgramme(Programme::factory()->create(), $mda)
                    ->create(['status' => 'active', 'created_by' => $creator->id])
                    ->id,
            ])->save();
        });
    }
}
