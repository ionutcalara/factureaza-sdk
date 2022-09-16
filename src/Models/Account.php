<?php

declare(strict_types=1);

/**
 * Contains the Account class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-16
 *
 */

namespace Konekt\Factureaza\Models;

use DateTimeImmutable;

class Account
{
    public readonly string $id;

    public readonly string $accountName;

    public readonly string $companyName;

    public readonly string $address1;

    public readonly string $address2;

    public readonly string $city;

    public readonly string $country;

    public readonly string $email;

    public readonly string $regNo;

    public readonly string $euid;

    public readonly string $companyState;

    public readonly float $totalOpen;

    public readonly float $totalOverdue;

    public readonly DateTimeImmutable $createdAt;

    public readonly DateTimeImmutable $updatedAt;
}
