<?php

declare(strict_types=1);

/**
 * Contains the Invoices trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-17
 *
 */

namespace Konekt\Factureaza\Endpoints;

use Konekt\Factureaza\Models\Invoice;
use Konekt\Factureaza\Models\InvoiceItem;
use Konekt\Factureaza\Requests\CreateInvoice;

trait Invoices
{
    public function createInvoice(CreateInvoice $invoice): Invoice
    {
        $response = $this->mutate($invoice);

        $items = [];
        foreach ($response->json('data')['createInvoice']['documentPositions'] ?? [] as $documentPosition) {
            $items[] = new InvoiceItem($this->remap($documentPosition, InvoiceItem::class));
        }

        return new Invoice(
            array_merge(
                $this->remap($response->json('data')['createInvoice'] ?? [], Invoice::class),
                ['items' => $items],
            )
        );
    }
}
