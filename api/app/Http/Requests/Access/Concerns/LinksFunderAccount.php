<?php

declare(strict_types=1);

namespace App\Http\Requests\Access\Concerns;

use App\Domain\Access\Enums\MdaType;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use Illuminate\Contracts\Validation\Validator;

/**
 * Pairing rules for an organisation's funder account (§11, revised).
 *
 * Only a PARTNER organisation funds through an account of its own — a ministry has
 * no funder identity, and letting one carry a link would make "is this government?"
 * answerable two contradictory ways.
 *
 * The account is also exclusive: one Development Partner login belongs to one
 * organisation. The database says so too (unique index), but a constraint violation
 * surfaces as a 500, and an administrator who picked an account already in use
 * deserves to be told which organisation has it.
 */
trait LinksFunderAccount
{
    protected function validateFunderAccount(Validator $validator): void
    {
        if ($validator->errors()->hasAny(['type', 'funder_user_id'])) {
            return;
        }

        $funder = $this->input('funder_user_id');
        if ($funder === null || $funder === '') {
            return;
        }

        $type = $this->resolveType();
        if ($type !== null && $type !== MdaType::Partner) {
            $validator->errors()->add(
                'funder_user_id',
                'Only a development partner organisation funds through an account. Set the type to development partner first.',
            );

            return;
        }

        $taken = Mda::query()->withoutGlobalScope(MdaScope::class)
            ->where('funder_user_id', $funder)
            ->when($this->currentMdaId() !== null, fn ($q) => $q->whereKeyNot($this->currentMdaId()))
            ->value('name');

        if ($taken !== null) {
            $validator->errors()->add('funder_user_id', "That funding account already belongs to {$taken}.");
        }
    }

    /** The type this request will leave the organisation with. */
    private function resolveType(): ?MdaType
    {
        $given = $this->input('type');
        if (is_string($given) && $given !== '') {
            return MdaType::tryFrom($given);
        }

        // An edit that changes only the link keeps the stored type.
        $id = $this->currentMdaId();

        return $id === null
            ? null
            : Mda::query()->withoutGlobalScope(MdaScope::class)->find($id)?->type;
    }

    private function currentMdaId(): ?string
    {
        $mda = $this->route('mda');
        if ($mda === null) {
            return null;
        }

        return is_string($mda) ? $mda : $mda->id;
    }
}
