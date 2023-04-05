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

    public string $name;

    public bool $isCompany;

    public string $address;

    public ?string $address2;

    public ?string $zip;

    public string $city;

    public ?string $province;

    public string $country;

    public ?string $email;

    public ?string $phone;

    public ?string $regNo;

    public ?string $taxNo;

    public ?string $taxNoPrefix;

    public static function attributeMap(): array
    {
        return [
            'country' => ['country', fn (array $country) => $country['iso']],
            'registrationId' => 'regNo',
            'uid' => 'taxNo',
            'taxId' => 'taxNoPrefix',
            'state' => 'province',
            'telephone' => 'phone',
        ];
    }
}
