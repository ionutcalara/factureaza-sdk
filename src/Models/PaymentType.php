<?php

declare(strict_types=1);

/**
 * Contains the PaymentType class.

 *
 */

namespace Konekt\Factureaza\Models;

use Konekt\Enum\Enum;

/**
 * @method static PaymentType CARD()
 * @method static PaymentType BANK_TRANSFER()
 * @method static PaymentType POST_MANDATE()

 */
class PaymentType extends Enum
{
    public const __DEFAULT = self::CARD;

    public const CARD = 'Card';
    public const BANK_TRANSFER = 'Ordin de plată / transfer bancar';
    public const POST_MANDATE = 'Mandat poștal';
}
