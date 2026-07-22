<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

/**
 * Deterministic keyed hash for national identifiers (SECURITY.md §4, NFR-SEC-01).
 *
 * NIN/BVN are encrypted at rest (non-deterministic), so equality lookups — exact
 * duplicate matching, the lookup/serve seam, uniqueness — run against an
 * HMAC-SHA256 of the normalised identifier stored beside the ciphertext
 * (`nin_hash`/`bvn_hash`). The HMAC key is derived from the app key via HKDF, so
 * no extra secret is needed; rotating APP_KEY therefore requires re-encrypting
 * AND re-hashing the identifier columns (see docs/SECURITY-FINDINGS.md).
 *
 * The hash is keyed precisely so a leaked table cannot be reversed by brute-forcing
 * the 11-digit space (a plain SHA-256 of an 11-digit number would fall in seconds).
 */
final class IdentifierHasher
{
    private static ?string $key = null;

    public static function hash(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return hash_hmac('sha256', $value, self::key());
    }

    private static function key(): string
    {
        if (self::$key !== null) {
            return self::$key;
        }

        $appKey = (string) config('app.key');
        if (str_starts_with($appKey, 'base64:')) {
            $appKey = (string) base64_decode(substr($appKey, 7), true);
        }

        return self::$key = hash_hkdf('sha256', $appKey, 32, 'spmis.identifier-hash.v1');
    }

    /** Testing seam: clear the derived-key cache (e.g. after changing app.key). */
    public static function flush(): void
    {
        self::$key = null;
    }
}
