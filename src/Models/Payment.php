<?php

declare(strict_types=1);

/**
 * Contains the Invoice class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-17
 *
 */

namespace Konekt\Factureaza\Models;

use Carbon\CarbonImmutable;
use Konekt\Factureaza\Contracts\Resource;

class Payment implements Resource
{
    use HasDynamicAttributeConstructor;
    use HasId;
    use HasTimestamps;

    public readonly CarbonImmutable $paymentDate;

    public readonly ?PaymentType $paymentType;

    public readonly float $amount;

    public readonly string $currency;

    public readonly string $description;

    public static function attributeMap(): array
    {
        return [
        ];
    }
}
