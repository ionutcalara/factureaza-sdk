<?php

declare(strict_types=1);

/**
 * Contains the Clients trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-08
 *
 */

namespace Konekt\Factureaza\Endpoints;

use Konekt\Factureaza\Models\Payment;
use Konekt\Factureaza\Requests\CreatePayment;
use Konekt\Factureaza\Requests\GetPayment;
use Konekt\Factureaza\Requests\GetPaymentByInvoiceID;

trait Payments
{
	public function payment(string $id): ?Payment
	{
		$response = $this->query(new GetPayment($id));
		$data = $response->json('data')['payments'][0] ?? null;

		return is_null($data) ? null : new Payment($this->remap($data, Payment::class));
	}

	public function paymentByInvoiceId(string $invoiceId): ?Payment
	{
		$response = $this->query(new GetPaymentByInvoiceID($invoiceId));
		$data = $response->json('data')['payments'][0] ?? null;

		return is_null($data) ? null : new Payment($this->remap($data, Payment::class));
	}


	public function createPayment(array|CreatePayment $payment): Payment
	{
		$request = is_array($payment) ? CreatePayment::fromArray($payment) : $payment;

		$response = $this->mutate($request);

		return new Payment(
			$this->remap($response->json('data')['createPayment'], Payment::class)
		);
	}
}
