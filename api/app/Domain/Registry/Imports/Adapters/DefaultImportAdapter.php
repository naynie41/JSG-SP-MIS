<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports\Adapters;

use App\Domain\Registry\Enums\RegistrationSource;

/**
 * Adapter for plain Excel/CSV uploads whose columns already use the canonical
 * beneficiary field names. Provenance is set per batch (excel vs csv) at
 * construction, so the same mapping serves both.
 */
class DefaultImportAdapter extends FieldMappingAdapter
{
    public function __construct(private readonly RegistrationSource $source) {}

    public function source(): RegistrationSource
    {
        return $this->source;
    }

    protected function idKeys(): array
    {
        return [];
    }
}
