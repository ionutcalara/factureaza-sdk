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
use Konekt\Factureaza\Models\Invoice;
use Konekt\Factureaza\Requests\Concerns\RequestsClientFields;
use Konekt\Factureaza\Validation\SuperTinyArrayValidator;

class UpdateClient implements Mutation
{
    use RequestsClientFields;

    public string $id;

    public ?string $name = null;

    public ?bool $isCompany = null;

    public ?string $address = null;

    public ?string $address2 = null;

    public ?string $zip = null;

    public ?string $city = null;

    public ?string $province = null;

    public ?string $country = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $regNo = null;

    public ?string $taxNo = null;

    public ?string $taxNoPrefix = null;

    private static array $schema = [
		'id'=> 'string*',
        'name' => 'string',
        'isCompany' => 'bool',
        'address' => 'string',
        'address2' => 'string',
        'country' => 'string',
        'city' => 'string',
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
    public static function fromArray(array $data): UpdateClient
    {
        $result = new UpdateClient();

        foreach ($result->validate($data) as $field => $value) {
            $result->{$field} = $value;
        }

        return $result;
    }

    public function operation(): string
    {
        return 'updateClient';
    }

    public function payload(): array
    {
        $payload =  [
			'id' => $this->id,
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

		return array_filter($payload, fn($v) => !is_null($v));
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
