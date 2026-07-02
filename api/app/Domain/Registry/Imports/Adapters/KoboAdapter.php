<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports\Adapters;

use App\Domain\Registry\Enums\RegistrationSource;

/**
 * Kobo Collect submissions/exports (PRD FR-REG-02). Kobo names columns after the
 * XLSForm question names (often group-prefixed, e.g. `personal/first_name`) and
 * adds meta columns; the submission's identity comes from `_id` / `_uuid` /
 * `meta/instanceID`.
 */
class KoboAdapter extends FieldMappingAdapter
{
    public function source(): RegistrationSource
    {
        return RegistrationSource::Kobo;
    }

    protected function idKeys(): array
    {
        return ['_id', '_uuid', 'instanceid', 'uuid'];
    }
}
