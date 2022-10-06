<?php

declare(strict_types=1);

/**
 * Contains the HasTimestamps trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-06
 *
 */

namespace Konekt\Factureaza\Models;

use Carbon\CarbonImmutable;

trait HasTimestamps
{
    public readonly CarbonImmutable $createdAt;

    public readonly CarbonImmutable $updatedAt;
}
