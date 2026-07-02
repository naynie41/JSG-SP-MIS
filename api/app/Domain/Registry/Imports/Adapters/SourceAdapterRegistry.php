<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports\Adapters;

use App\Domain\Registry\Enums\RegistrationSource;
use InvalidArgumentException;

/**
 * Resolves the {@see RegistrationSourceAdapter} for a given provenance. New
 * sources (e.g. an existing government system) are added by registering an
 * adapter here — no change to the parse/validate/commit pipeline is required.
 */
class SourceAdapterRegistry
{
    /** @var array<string, RegistrationSourceAdapter> */
    private array $adapters = [];

    public function register(RegistrationSourceAdapter $adapter): void
    {
        $this->adapters[$adapter->source()->value] = $adapter;
    }

    public function for(RegistrationSource $source): RegistrationSourceAdapter
    {
        return $this->adapters[$source->value]
            ?? throw new InvalidArgumentException("No import adapter is registered for source: {$source->value}");
    }

    public function has(RegistrationSource $source): bool
    {
        return isset($this->adapters[$source->value]);
    }

    /**
     * The provenance values that can be uploaded as a file import.
     *
     * @return list<string>
     */
    public function importableSources(): array
    {
        return array_keys($this->adapters);
    }
}
