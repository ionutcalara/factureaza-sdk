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
use Konekt\Factureaza\Models\Client;
use Konekt\Factureaza\Models\DocumentState;
use Konekt\Factureaza\Requests\Concerns\RequestsInvoiceFields;

class CreateInvoice implements Mutation
{
    use RequestsInvoiceFields;

    public string $currency = 'RON';

    public string $exchangeRate = '1.0000';

    public string $dueDays = '30';

    public string $locale = 'ro';

    public string $clientId;

    public string $documentSeriesId;

    public ?string $upperAnnotation = null;

    public ?string $lowerAnnotation = null;

    public CarbonImmutable $documentDate;

    /** @var CreateInvoiceItem[] */
    public array $items = [];

    private Client|array|null $client = null;

    private DocumentState $state;

    public function __construct()
    {
        $this->documentDate = CarbonImmutable::now();
        $this->state = DocumentState::create();
    }

    public static function inSeries(string $seriesId): self
    {
        $instance = new self();
        $instance->documentSeriesId = $seriesId;

        return $instance;
    }

    public function operation(): string
    {
        return 'createInvoice';
    }

    public function payload(): array
    {
        return [
            'currency' => $this->currency,
            'exchangeRate' => $this->exchangeRate,
            'dueDays' => $this->dueDays,
            'locale' => $this->locale,
            'clientId' => $this->clientId,
            'documentSeriesId' => $this->documentSeriesId,
            'documentDate' => $this->documentDate->format('Y-m-d'),
            'documentState' => $this->state->value(),
            'upperAnnotation' => $this->upperAnnotation,
            'lowerAnnotation' => $this->lowerAnnotation,
            'documentPositions' => collect($this->items)->map->toPayload()->toArray(),
        ];
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
