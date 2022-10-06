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

class Invoice implements Resource
{
    use HasDynamicAttributeConstructor;
    use HasId;
    use HasTimestamps;

    public readonly CarbonImmutable $documentDate;

    public readonly string $clientId;

    /** @var InvoiceItem[] */
    public readonly array $items;

    public static function attributeMap(): array
    {
        return [];
    }
}
