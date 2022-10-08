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
    use HasId;
    use HasTimestamps;

    public readonly string $name;

    public readonly bool $isCompany;

    public readonly string $address;

    public readonly string $address2;

    public readonly string $zip;

    public readonly string $city;

    public readonly string $province;

    public readonly string $country;

    public readonly string $email;

    public readonly string $regNo;

    public readonly string $taxNo;

    public readonly string $taxNoPrefix;

    public static function attributeMap(): array
    {
        return [
            'country' => ['country', fn (array $country) => $country['iso']],
            'registrationId' => 'regNo',
            'uid' => 'taxNo',
            'taxId' => 'taxNoPrefix',
            'state' => 'province',
        ];
    }
}
