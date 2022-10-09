<?php

declare(strict_types=1);

/**
 * Contains the CreateClient class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-09
 *
 */

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Contracts\Mutation;
use Konekt\Factureaza\Exceptions\InvalidClientException;
use Konekt\Factureaza\Validation\SuperTinyArrayValidator;

class CreateClient implements Mutation
{
    use RequestsClientFields;

    public string $name;

    public bool $isCompany;

    public string $address;

    public ?string $address2 = null;

    public ?string $zip = null;

    public string $city;

    public ?string $province = null;

    public string $country = 'RO';

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $regNo = null;

    public ?string $taxNo = null;

    public ?string $taxNoPrefix = null;

    private static array $schema = [
        'name' => 'string*',
        'isCompany' => 'bool*',
        'address' => 'string*',
        'address2' => 'string',
        'country' => 'string:default=RO',
        'city' => 'string*',
        'zip' => 'string',
        'province' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'regNo' => 'string',
        'taxNo' => 'string',
        'taxNoPrefix' => 'string',
    ];

    /**
     * @param array{name: string, isCompany: bool, address: string, address2: string, zip: string, city: string, province: string, country: string, email: string, phone: string, regNo: string, taxNo:string, taxNoPrefix: string} $data
     *
     * @return CreateClient
     * @throws InvalidClientException
     */
    public static function fromArray(array $data): CreateClient
    {
        $result = new CreateClient();

        foreach ($result->validate($data) as $field => $value) {
            $result->{$field} = $value;
        }

        return $result;
    }

    public function operation(): string
    {
        return 'createClient';
    }

    public function payload(): array
    {
        return [
            'name' => $this->name,
            'isCompany' => $this->isCompany,
            'registrationId' => $this->regNo,
            'uid' => $this->taxNo,
            'taxId' => $this->taxNoPrefix,
            'address' => $this->address,
            'address2' => $this->address2,
            'city' => $this->city,
            'zip' => $this->zip,
            'state' => $this->province,
            'country' => $this->country,
            'email' => $this->email,
            'telephone' => $this->phone,
        ];
    }

    /**
     * @throws \Konekt\Factureaza\Exceptions\InvalidClientException
     */
    private function validate(array $data): array
    {
        return SuperTinyArrayValidator::createFor('client')
            ->onErrorThrow(InvalidClientException::class)
            ->validate(self::$schema, $data);
    }
}
