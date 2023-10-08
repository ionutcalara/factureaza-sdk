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
use Konekt\Factureaza\Models\Payment;
use Konekt\Factureaza\Requests\CreateInvoice;
use Konekt\Factureaza\Requests\ExportInvoiceAsEFactura;
use Konekt\Factureaza\Requests\GetInvoice;
use Konekt\Factureaza\Requests\GetInvoiceAsPdf;
use Konekt\Factureaza\Requests\SubmitInvoiceToAnafAsEFactura;
use Konekt\Factureaza\Requests\UpdateInvoice;

trait Invoices
{
	public function createInvoice(CreateInvoice $invoice): ?Invoice
	{
		$response = $this->mutate($invoice);

		return $this->rawApiDataToInvoice($response->json('data')['createInvoice'] ?? null);
	}

	public function updateInvoice(UpdateInvoice $invoice): ?Invoice
	{
		$response = $this->mutate($invoice);

		return $this->rawApiDataToInvoice($response->json('data')['updateInvoice'] ?? null);
	}

	public function invoiceAsPdfBase64(string $invoiceId): ?string
	{
		return $this->query(new GetInvoiceAsPdf($invoiceId))
			->json('data')['invoices'][0]['pdfContent'] ?? null;
	}

	public function invoiceAsEFactura(ExportInvoiceAsEFactura $exportInvoiceAsEFactura): ?string
	{
		$response = $this->mutate($exportInvoiceAsEFactura);

		return $response->json('data')['exportInvoiceEfacturaUbl']['xml'] ?? null;
	}

	public function submitInvoiceToAnafAsEFactura(SubmitInvoiceToAnafAsEFactura $submitInvoiceAsEFactura): ?string
	{
		$response = $this->mutate($submitInvoiceAsEFactura);

		return $response->json('data')['submitInvoiceEfacturaUbl']['efacturaTransaction'] ?? null;
	}

	public function invoice(string $invoiceId): ?Invoice
	{
		return $this->rawApiDataToInvoice(
			$this->query(new GetInvoice($invoiceId))
				->json('data')['invoices'][0] ?? null
		);
	}

	private function rawApiDataToInvoice(?array $data): ?Invoice
	{
		if(null === $data) {
			return null;
		}

		$items = [];
		foreach($data['documentPositions'] ?? [] as $documentPosition) {
			$items[] = new InvoiceItem($this->remap($documentPosition, InvoiceItem::class));
		}

		$payments = [];
		foreach($data['payments'] ?? [] as $payment) {
			$payments[] = new Payment($this->remap($payment, Payment::class));
		}

		return new Invoice(
			array_merge(
				$this->remap($data, Invoice::class),
				[
					'items' => $items,
					'payments' => $payments
				]
			)
		);
	}
}
