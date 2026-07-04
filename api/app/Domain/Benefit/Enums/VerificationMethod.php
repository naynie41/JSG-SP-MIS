<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Enums;

/**
 * How a benefit delivery was verified (PRD FR-BEN-04). `field_confirmation` and
 * `signature` are implemented now; `otp` and `biometric` are recognised but
 * require external access (SMS/OTP gateway, NIMC/biometric) and are stubbed as
 * UNAVAILABLE behind the verifier interface until that access is provided.
 */
enum VerificationMethod: string
{
    case None = 'none';
    case FieldConfirmation = 'field_confirmation';
    case Signature = 'signature';
    case Otp = 'otp';
    case Biometric = 'biometric';
}
