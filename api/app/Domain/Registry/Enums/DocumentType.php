<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * Type of a supporting document attached to a beneficiary (PRD FR-REG-07).
 */
enum DocumentType: string
{
    case NationalId = 'national_id';
    case BirthCertificate = 'birth_certificate';
    case ProofOfResidence = 'proof_of_residence';
    case PassportPhoto = 'passport_photo';
    case Attestation = 'attestation';
    case Other = 'other';

    public function label(): string
    {
        return str($this->value)->replace('_', ' ')->title()->value();
    }
}
