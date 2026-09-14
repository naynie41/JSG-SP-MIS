<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme\Concerns;

use App\Domain\Programme\Enums\FundingType;
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
    }
}
