<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

/**
 * Where the untouched source row is carried alongside the normalized one during
 * validation, so an error message can quote what the file actually said (FR-REG-16).
 *
 * A class rather than a constant on {@see QuotesOriginalInput}: PHP does not allow a
 * trait constant to be read through the trait's name, and both the rules and the
 * importer that fills it need one shared spelling of the key.
 */
final class OriginalInput
{
    /**
     * Deliberately underscore-prefixed: it shares a namespace with canonical field names
     * in the validated array, and no canonical field is or will be spelled this way.
     */
    public const KEY = '__original';
}
