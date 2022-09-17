<?php

declare(strict_types=1);

/**
 * Contains the Resource interface.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-17
 *
 */

namespace Konekt\Factureaza\Contracts;

interface Resource
{
    public static function attributeMap(): array;
}
