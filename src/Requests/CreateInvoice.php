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

class CreateInvoice
{
    public string $currency = 'RON';

    public string $clientId;

    public string $documentSeriesId;

    public CarbonImmutable $documentDate;

    /** @var CreateInvoiceItem[] */
    public array $items = [];

    public function __construct()
    {
        $this->documentDate = CarbonImmutable::now();
    }

    public static function inSeries(string $seriesId): self
    {
        $instance = new self();
        $instance->documentSeriesId = $seriesId;

        return $instance;
    }

    public function forClient(): self
    {
        return $this;
    }

    public function withEmissionDate(string|DateTimeInterface $date): self
    {
        $this->documentDate = is_string($date) ? CarbonImmutable::createFromFormat('Y-m-d', $date) : CarbonImmutable::createFromInterface($date);

        return $this;
    }

    public function addItem(string $item): self
    {
        return $this;
    }

}
