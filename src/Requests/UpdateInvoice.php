<?php

declare(strict_types=1);

/**
 * Contains the CreateInvoice class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-17
 *
 */

namespace Konekt\Factureaza\Requests;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use Konekt\Factureaza\Contracts\Mutation;
use Konekt\Factureaza\Exceptions\InvalidClientException;
use Konekt\Factureaza\Models\Client;
use Konekt\Factureaza\Models\DocumentState;
use Konekt\Factureaza\Models\Invoice;
use Konekt\Factureaza\Requests\Concerns\RequestsInvoiceFields;
use Konekt\Factureaza\Validation\SuperTinyArrayValidator;

class UpdateInvoice implements Mutation
{
    use RequestsInvoiceFields;

	public string $id;
    public ?string $currency = null;

    public ?string $exchangeRate = null;

    public ?string $dueDays = null;

    public ?string $locale = null;

    public ?string $clientId = null;
    public ?string $clientCountry = null;
    public ?string $clientName = null;
    public ?string $clientAddress = null;
    public ?string $clientAddress2 = null;
    public ?string $clientCity = null;
    public ?string $clientState = null;
    public ?string $clientZip = null;

    public ?string $documentSeriesId = null;

    public ?string $upperAnnotation = null;

    public ?string $lowerAnnotation = null;

    public ?CarbonImmutable $documentDate = null;

    /** @var CreateInvoiceItem[] */
    public ?array $items = [];

    private Client|array|null $client = null;

    private ?DocumentState $state = null;


    public function operation(): string
    {
        return 'updateInvoice';
    }

	public static function fromArray(array $data): UpdateInvoice
	{
		$result = new UpdateInvoice();

		foreach ($data as $field => $value) {
			$result->{$field} = $value;
		}

		return $result;
	}

	public function itemsFromOriginal(Invoice $invoice): self
	{
		foreach($invoice->items as $item) {
			$this->addItem((array) $item);
		}

		return $this;
	}

    public function payload(): array
    {
        $payload =  [
			'id' => $this->id,
            'currency' => $this->currency,
            'exchangeRate' => $this->exchangeRate,
            'dueDays' => $this->dueDays,
            'locale' => $this->locale,
            'clientId' => $this->clientId,
            'documentSeriesId' => $this->documentSeriesId,
            'documentDate' => $this->documentDate ? $this->documentDate->format('Y-m-d') : null,
            'documentState' => $this->state ? $this->state->value() : null,
	        'clientCountry' => $this->clientCountry,
	        'clientName' => $this->clientName,
	        'clientAddress' => $this->clientAddress,
	        'clientAddress2' => $this->clientAddress2,
	        'clientCity' => $this->clientCity,
	        'clientState' => $this->clientState,
	        'clientZip' => $this->clientZip,
            'upperAnnotation' => $this->upperAnnotation,
            'lowerAnnotation' => $this->lowerAnnotation,
            'documentPositions' => collect($this->items)->map->toPayload()->toArray(),
        ];

	    return array_filter($payload, fn($v) => !is_null($v));
    }

    public function forClient(string|Client $client): self
    {
        if (is_string($client)) {
            $this->clientId = $client;
            $this->client = null;
        } else {
            $this->client = $client;
            $this->clientId = $client->id;
        }

        return $this;
    }

    public function withEmissionDate(string|DateTimeInterface $date): self
    {
        $this->documentDate = is_string($date) ? CarbonImmutable::createFromFormat('Y-m-d', $date) : CarbonImmutable::createFromInterface($date);

        return $this;
    }

    public function withLowerAnnotation(string $text): self
    {
        $this->lowerAnnotation = $text;

        return $this;
    }

    public function withUpperAnnotation(string $text): self
    {
        $this->upperAnnotation = $text;

        return $this;
    }

    public function asDraft(): self
    {
        $this->state = DocumentState::DRAFT();

        return $this;
    }

    public function asClosed(): self
    {
        $this->state = DocumentState::CLOSED();

        return $this;
    }

    public function asOpen(): self
    {
        $this->state = DocumentState::OPEN();

        return $this;
    }

    public function asCancelled(): self
    {
        $this->state = DocumentState::CANCELLED();

        return $this;
    }

    /**
     * @param CreateInvoiceItem|array{description: string, unit: string, quantity: float, price: float, productCode: string, vat: string} $item
     *
     * @return $this
     */
    public function addItem(array|CreateInvoiceItem $item): self
    {
        if (is_array($item)) {
            $item = CreateInvoiceItem::fromArray($item);
        }

        $this->items[] = $item;

        return $this;
    }
}
