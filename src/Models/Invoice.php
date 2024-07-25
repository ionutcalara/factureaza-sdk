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

use Carbon\CarbonImmutable;
use Konekt\Factureaza\Contracts\Resource;

class Invoice implements Resource
{
    use HasDynamicAttributeConstructor;
    use HasId;
    use HasTimestamps;

    public readonly CarbonImmutable $documentDate;

    public readonly DocumentState $state;

    public readonly ?string $companyReferenceCodeSource;
    public readonly ?string $clientId;

	public readonly ?string $clientName;

	public readonly ?string $clientAddress;

	public readonly ?string $clientAddress2;

	public readonly ?string $clientCity;

	public readonly ?string $clientState;

	public readonly ?string $clientZip;
	public readonly ?bool $clientIsCompany;

    public readonly string $number;

    public readonly float $total;

    public readonly string $currency;

    public readonly string $hashcode;

    public readonly ?string $upperAnnotation;

    public readonly ?string $lowerAnnotation;

    /** @var InvoiceItem[] */
    public readonly array $items;

	/** @var Payment[] */
	public readonly array $payments;

	public readonly string $xml;

    public static function attributeMap(): array
    {
        return [
            'series' => 'number',
            'documentState' => 'state',
        ];
    }
}
