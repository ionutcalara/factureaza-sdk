<?php

declare(strict_types=1);

/**
 * Contains the Client class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-06
 *
 */

namespace Konekt\Factureaza\Models;

use Konekt\Factureaza\Contracts\Resource;

class Client implements Resource
{
    use HasDynamicAttributeConstructor;

    public static function attributeMap(): array
    {
        return [];
    }
}
