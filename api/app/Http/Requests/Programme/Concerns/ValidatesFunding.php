<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme\Concerns;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Enums\FundingType;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Rules\IsFundingPartner;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

/**
 * How an activity is funded: the same rules on every path that writes an activity
 * (direct create, edit, and the upload wizard's draft).
 *
 * The three fields travel together. A partner link decides which partner can see the
 * activity, so it is only valid on a partner activity, and "co-funded with government"
 * only means something alongside a partner. Requiring all three whenever any is sent is
 * what lets an edit be checked without reading the stored activity: a request can never
 * change the partner without also stating the funding type it belongs to.
 */
trait ValidatesFunding
{
    /**
     * @return array<string, mixed>
     */
    protected function fundingRules(): array
    {
        return [
            'funding_type' => ['nullable', Rule::enum(FundingType::class), 'required_with:funding_partner_id,co_funded_by_government'],
            'funding_partner_id' => ['nullable', 'uuid', new IsFundingPartner],
            'co_funded_by_government' => ['sometimes', 'boolean'],
        ];
    }

    protected function validateFunding(Validator $validator): void
    {
        if ($validator->errors()->hasAny(['funding_type', 'funding_partner_id', 'co_funded_by_government'])) {
            return;
        }

        if (! $this->hasAny(['funding_type', 'funding_partner_id', 'co_funded_by_government'])) {
            return;
        }

        $type = FundingType::tryFrom((string) $this->input('funding_type'));
        $partner = $this->input('funding_partner_id');
        $hasPartner = $partner !== null && $partner !== '';

        if ($type === FundingType::Partner && ! $hasPartner) {
            $validator->errors()->add('funding_partner_id', 'Choose the partner funding this activity.');
        }

        if ($type !== FundingType::Partner && $hasPartner) {
            $validator->errors()->add('funding_partner_id', 'Only an activity funded by a social protection partner can be linked to a partner.');
        }

        if ($type !== FundingType::Partner && $this->boolean('co_funded_by_government')) {
            $validator->errors()->add('co_funded_by_government', 'Co-funding with government applies only to an activity funded by a partner.');
        }

        $this->refuseGovernmentFundingOfAPartnersActivity($validator, $type);
    }

    /**
     * Government does not fund a partner organisation's own activity (stakeholder
     * decision, 2026-09-20).
     *
     * A partner that implements owns its activities exactly as an MDA does, so
     * nothing in the shape of the record stops it being marked government-funded.
     * The rule does: on an activity owned by a partner organisation, government
     * funding — whether as the funding type or as co-funding — is refused. Funding
     * for that work is the partner's own, or another partner's.
     */
    private function refuseGovernmentFundingOfAPartnersActivity(Validator $validator, ?FundingType $type): void
    {
        $claimsGovernment = $type === FundingType::Government || $this->boolean('co_funded_by_government');
        if (! $claimsGovernment) {
            return;
        }

        // The owner is the caller's own MDA on create; on an edit the activity names it.
        $owner = $this->owningMdaForFunding();

        if ($owner !== null && ! $owner->isGovernment()) {
            $field = $type === FundingType::Government ? 'funding_type' : 'co_funded_by_government';
            $validator->errors()->add(
                $field,
                'Government does not fund a partner organisation\'s activity. Record it as partner or individual funding.',
            );
        }
    }

    /** The organisation that will own this activity, or null if it cannot be resolved. */
    private function owningMdaForFunding(): ?Mda
    {
        $activity = $this->route('activity');
        if ($activity !== null) {
            $owned = Activity::query()->withoutGlobalScope(MdaScope::class)
                ->whereKey(is_string($activity) ? $activity : $activity->id)
                ->value('owner_mda_id');

            return $owned === null ? null : Mda::query()->withoutGlobalScope(MdaScope::class)->find($owned);
        }

        return $this->user()?->mda;
    }
}
