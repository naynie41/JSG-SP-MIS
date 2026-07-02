<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * The 27 Local Government Areas of Jigawa State. Registration is state-scoped, so
 * an incoming LGA must be one of these (PRD FR-REG-04/05). Wards are far more
 * numerous and vary by LGA, so they stay free-text (validated for shape only)
 * pending a ward reference table.
 */
enum Lga: string
{
    case Auyo = 'auyo';
    case Babura = 'babura';
    case Biriniwa = 'biriniwa';
    case BirninKudu = 'birnin_kudu';
    case Buji = 'buji';
    case Dutse = 'dutse';
    case Gagarawa = 'gagarawa';
    case Garki = 'garki';
    case Gumel = 'gumel';
    case Guri = 'guri';
    case Gwaram = 'gwaram';
    case Gwiwa = 'gwiwa';
    case Hadejia = 'hadejia';
    case Jahun = 'jahun';
    case KafinHausa = 'kafin_hausa';
    case Kaugama = 'kaugama';
    case Kazaure = 'kazaure';
    case KiriKasama = 'kiri_kasama';
    case Kiyawa = 'kiyawa';
    case Maigatari = 'maigatari';
    case MalamMadori = 'malam_madori';
    case Miga = 'miga';
    case Ringim = 'ringim';
    case Roni = 'roni';
    case SuleTankarkar = 'sule_tankarkar';
    case Taura = 'taura';
    case Yankwashi = 'yankwashi';

    /** Human-readable name (e.g. "Birnin Kudu", "Kafin Hausa"). */
    public function label(): string
    {
        return str($this->value)->replace('_', ' ')->title()->value();
    }
}
