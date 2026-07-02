<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports\Adapters;

use App\Domain\Registry\Enums\RegistrationSource;

/**
 * ODK (Central/Collect) submissions/exports (PRD FR-REG-02). ODK exports use
 * similar question-name columns with `-`/`/` group separators and carry the
 * instance identity in `instanceID` / `meta-instanceID` / `__id` / `KEY`.
 */
class OdkAdapter extends FieldMappingAdapter
{
    public function source(): RegistrationSource
    {
        return RegistrationSource::Odk;
    }

    protected function idKeys(): array
    {
        return ['instanceid', '__id', 'key'];
    }
}
