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
use Konekt\Factureaza\Requests\GetInvoiceAsPdf;

trait Invoices
{
    public function createInvoice(CreateInvoice $invoice): ?Invoice
    {
        $response = $this->mutate($invoice);

        $items = [];
        foreach ($response->json('data')['createInvoice']['documentPositions'] ?? [] as $documentPosition) {
            $items[] = new InvoiceItem($this->remap($documentPosition, InvoiceItem::class));
        }

        $data = $response->json('data')['createInvoice'] ?? null;

        return is_null($data) ? null : new Invoice(array_merge($this->remap($data, Invoice::class), ['items' => $items]));
    }

    public function invoiceAsPdfBase64(string $invoiceId): ?string
    {
        return $this->query(new GetInvoiceAsPdf($invoiceId))
            ->json('data')['invoices'][0]['pdfContent'] ?? null;
    }
}
