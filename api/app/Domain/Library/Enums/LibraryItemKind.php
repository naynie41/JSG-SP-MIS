<?php

declare(strict_types=1);

namespace App\Domain\Library\Enums;

/**
 * Whether a library item CARRIES a file or POINTS at one.
 *
 * Exclusive by design, enforced at three levels (request, model, and a Postgres
 * CHECK). A row that is both would leave the public page unable to say what the
 * button does; a row that is neither is a card that cannot be opened.
 */
enum LibraryItemKind: string
{
    /** An uploaded document, stored privately and streamed by the API. */
    case File = 'file';

    /** A URL elsewhere — nothing is stored, and the link opens off-site. */
    case Link = 'link';

    public function label(): string
    {
        return match ($this) {
            self::File => 'Uploaded file',
            self::Link => 'External link',
        };
    }
}
