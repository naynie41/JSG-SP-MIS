<?php

declare(strict_types=1);

namespace App\Domain\Ops\Backup;

use RuntimeException;

/**
 * Authenticated encryption for backup artifacts (NFR-AVAIL-01, SECURITY.md §4).
 * AES-256-CBC with a random IV, wrapped in an encrypt-then-MAC envelope
 * (HMAC-SHA256) so a tampered or wrongly-keyed artifact fails loudly on restore
 * instead of yielding garbage. Keys are derived from the configured backup key via
 * SHA-256, so any sufficiently random key string works; the enc and MAC keys are
 * domain-separated.
 *
 * Envelope layout: [ mac(32) | iv(16) | ciphertext ].
 */
class BackupCipher
{
    private const CIPHER = 'aes-256-cbc';

    private const MAGIC = "SPMISBK1\0"; // versioned header, authenticated by the MAC

    public function __construct(private readonly ?string $key) {}

    public function isConfigured(): bool
    {
        return is_string($this->key) && $this->key !== '';
    }

    public function encrypt(string $plaintext): string
    {
        $iv = random_bytes(16);
        $ciphertext = openssl_encrypt($plaintext, self::CIPHER, $this->encKey(), OPENSSL_RAW_DATA, $iv);
        if ($ciphertext === false) {
            throw new RuntimeException('Backup encryption failed.');
        }

        $body = self::MAGIC.$iv.$ciphertext;

        return hash_hmac('sha256', $body, $this->macKey(), true).$body;
    }

    public function decrypt(string $envelope): string
    {
        if (strlen($envelope) < 32 + strlen(self::MAGIC) + 16) {
            throw new RuntimeException('Backup artifact is truncated or corrupt.');
        }

        $mac = substr($envelope, 0, 32);
        $body = substr($envelope, 32);
        if (! hash_equals(hash_hmac('sha256', $body, $this->macKey(), true), $mac)) {
            throw new RuntimeException('Backup integrity check failed (wrong key or tampered artifact).');
        }

        $magicLen = strlen(self::MAGIC);
        if (substr($body, 0, $magicLen) !== self::MAGIC) {
            throw new RuntimeException('Unrecognised backup format.');
        }

        $iv = substr($body, $magicLen, 16);
        $ciphertext = substr($body, $magicLen + 16);
        $plaintext = openssl_decrypt($ciphertext, self::CIPHER, $this->encKey(), OPENSSL_RAW_DATA, $iv);
        if ($plaintext === false) {
            throw new RuntimeException('Backup decryption failed.');
        }

        return $plaintext;
    }

    private function encKey(): string
    {
        return hash('sha256', $this->requireKey().'|enc', true);
    }

    private function macKey(): string
    {
        return hash('sha256', $this->requireKey().'|mac', true);
    }

    private function requireKey(): string
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('BACKUP_ENCRYPTION_KEY is not set — refusing to write an unencrypted backup.');
        }

        return (string) $this->key;
    }
}
