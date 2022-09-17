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

use Konekt\Factureaza\Contracts\Resource;

class Invoice implements Resource
{
    use HasDynamicAttributeConstructor;

    public readonly string $id;

    public static function attributeMap(): array
    {
        return [];
    }
}
