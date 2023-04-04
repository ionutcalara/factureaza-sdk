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
use Konekt\Factureaza\Models\Invoice;
use Konekt\Factureaza\Models\PaymentType;
use Konekt\Factureaza\Requests\Concerns\RequestsPaymentFields;

class CreatePayment implements Mutation
{
	use RequestsPaymentFields;

	public string $currency = 'RON';

	public string $description = '';

	public string $amount;

	public string $invoiceId;
	public string $proformaInvoiceId = '';

	public CarbonImmutable $paymentDate;

	private Invoice|array|null $invoice = null;

	private PaymentType $paymentType;

	public function __construct()
	{
		$this->paymentDate = CarbonImmutable::now();
		$this->paymentType = PaymentType::create();
	}

	public function operation(): string
	{
		return 'createPayment';
	}

	public function payload(): array
	{
		return [
			'currency' => $this->currency,
			'description' => $this->description,
			'amount' => $this->amount,
			'invoiceId' => $this->invoiceId,
			'proformaInvoiceId' => $this->proformaInvoiceId,
			'paymentDate' => $this->paymentDate,
			'paymentType' => $this->paymentType->value(),
		];
	}


	public static function forInvoice(string|Invoice $invoice): self
	{
		$instance = new self();

		if(is_string($invoice)) {
			$instance->invoiceId = $invoice;
			$instance->invoice = null;
		} else {
			$instance->invoice = $invoice;
			$instance->invoiceId = $invoice->id;
		}

		return $instance;
	}

	public function withPaymentDate(string|DateTimeInterface $date): self
	{
		$this->paymentDate = is_string($date) ? CarbonImmutable::createFromFormat('Y-m-d', $date) : CarbonImmutable::createFromInterface($date);

		return $this;
	}

	public function withPaymentType(PaymentType $paymentType): self
	{
		$this->paymentType = $paymentType;

		return $this;
	}

	public function withAmount(string $amount): self
	{
		$this->amount = $amount;

		return $this;
	}

}
