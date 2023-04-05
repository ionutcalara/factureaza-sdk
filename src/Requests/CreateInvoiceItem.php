<?php

declare(strict_types=1);

/**
 * Contains the CreateInvoiceItem class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-06
 *
 */

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Exceptions\InvalidInvoiceItemException;
use Konekt\Factureaza\Validation\SuperTinyArrayValidator;

class CreateInvoiceItem
{
    private string $description;

    private string $unit;

    private float $quantity = 1;

    private float $price;

    private ?string $productCode = null;

    private ?string $vat = '19';

    private static array $schema = [
        'description' => 'string*',
        'unit' => 'string*',
        'quantity' => 'number:default=1',
        'price' => 'number*',
        'productCode' => 'string*',
        'vat' => 'string',
    ];

    /**
     * @param array{description: string, unit: string, quantity: float, price: float, productCode: string, vat: string} $item
     *
     * @return CreateInvoiceItem
     * @throws InvalidInvoiceItemException
     */
    public static function fromArray(array $item): CreateInvoiceItem
    {
        $result = new CreateInvoiceItem();

        foreach ($result->validate($item) as $field => $value) {
            $result->{$field} = $value;
        }

        return $result;
    }

    public function toPayload(): array
    {
        return [
            'description' => $this->description,
            'unit' => $this->unit,
            'unitCount' => number_format($this->quantity, 2, '.', ''),
            'price' => number_format($this->price, 2, '.', ''),
            'productCode' => $this->productCode,
            'vat' => $this->vat ?? null,
        ];
    }

    /**
     * @throws InvalidInvoiceItemException
     */
    private function validate(array $data): array
    {
        return SuperTinyArrayValidator::createFor('invoice item')
            ->onErrorThrow(InvalidInvoiceItemException::class)
            ->validate(self::$schema, $data);
    }
}
