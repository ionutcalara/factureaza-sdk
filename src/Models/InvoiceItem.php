<?php

declare(strict_types=1);

/**
 * Contains the InvoiceItem class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-06
 *
 */

namespace Konekt\Factureaza\Models;

use Konekt\Factureaza\Contracts\Resource;

class InvoiceItem implements Resource
{
    use HasDynamicAttributeConstructor;
    use HasId;
    use HasTimestamps;

    public readonly string $description;

    public readonly float $price;

    public readonly string $unit;

    public readonly float $quantity;
    public readonly float $vat;

    public readonly ?string $productCode;

    public static function attributeMap(): array
    {
        return [
            'unitCount' => 'quantity',
        ];
    }
}
