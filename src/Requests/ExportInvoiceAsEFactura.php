<?php

declare(strict_types=1);

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Contracts\Mutation;

class ExportInvoiceAsEFactura implements Mutation
{
	public string $id;

	/**
	 * @link https://docs.peppol.eu/poacc/billing/3.0/codelist/UNCL1001-inv/
	 */
	public string $invoiceTypeCode = '380';

	/**
	 * @see https://docs.peppol.eu/poacc/billing/3.0/codelist/UNCL5305/
	 */
	public string $vatType = 'S';

	/**
	 * @link https://docs.peppol.eu/poacc/billing/3.0/codelist/UNCL4461/
	 */
	public string $paymentMethod = '1'; // Undefined instrument

	/** @var EFacturaDocumentClassificationCodeAttribute[] */
	public array $efacturaClassificationCodeAttributes = [];

	/** @var EFacturaDocumentPositionUnitsAttribute[] */
	public array $efacturaDocumentPositionUnitsAttributes = [];

	public string $invoiceNotes = ' '; // error when null

	public ?string $vatExemptionReason = null;

	public ?string $vatExemptionReasonDescription = null;

	public ?string $accountDocumentCounty = null;

	public ?string $accountDocumentBucharestCity = null;

	public ?string $clientDocumentCounty = null;

	public ?string $clientDocumentBucharestCity = null;

	public ?string $contractReference = null;

	public ?string $orderReference = null;

	public ?string $convertToDomesticCurrency = null;


	public static function forInvoice(string $invoiceId): self
	{
		$instance = new self();
		$instance->id = $invoiceId;

		return $instance;
	}

	public function fields(): array
	{
		return [
			'xml'
		];
	}

	public function operation(): string
	{
		return 'exportInvoiceEfacturaUbl';
	}

	public function payload(): array
	{
		$payload = [
			'id' => $this->id,
			'invoiceTypeCode' => $this->invoiceTypeCode,
			'vatType' => $this->vatType,
			'paymentMethod' => $this->paymentMethod,
			'invoiceNotes' => $this->invoiceNotes,
			'efacturaClassificationCodeAttributes' => collect($this->efacturaClassificationCodeAttributes)->map->toPayload()->toArray(),
			'efacturaDocumentPositionUnitsAttributes' => collect($this->efacturaDocumentPositionUnitsAttributes)->map->toPayload()->toArray(),
			'vatExemptionReason' => $this->vatExemptionReason,
			'vatExemptionReasonDescription' => $this->vatExemptionReasonDescription,
			'accountDocumentCounty' => $this->accountDocumentCounty,
			'accountDocumentBucharestCity' => $this->accountDocumentBucharestCity,
			'clientDocumentCounty' => $this->clientDocumentCounty,
			'clientDocumentBucharestCity' => $this->clientDocumentBucharestCity,
			'contractReference' => $this->contractReference,
			'orderReference' => $this->orderReference,
			'convertToDomesticCurrency' => $this->convertToDomesticCurrency
		];

		return array_filter($payload, fn($v) => !is_null($v) && !(is_array($v) && empty($v)));
	}

	public function withInvoiceTypeCode(string $invoiceTypeCode): self
	{
		$this->invoiceTypeCode = $invoiceTypeCode;

		return $this;
	}

	public function withVatType(string $vatType): self
	{
		$this->vatType = $vatType;

		return $this;
	}

	public function withPaymentMethod(string $paymentMethod): self
	{
		$this->paymentMethod = $paymentMethod;

		return $this;
	}

	public function withPaymentMethodDebitCard(): self
	{
		return $this->withPaymentMethod('55');
	}

	public function withPaymentMethodBankTransfer(): self
	{
		return $this->withPaymentMethod('42');
	}

	public function withInvoiceNotes($invoiceNotes)
	{
		$this->invoiceNotes = $invoiceNotes;

		return $this;
	}

	public function withVatExemptionReason($vatExemptionReason)
	{
		$this->vatExemptionReason = $vatExemptionReason;

		return $this;
	}

	public function withVatExemptionReasonDescription($vatExemptionReasonDescription)
	{
		$this->vatExemptionReasonDescription = $vatExemptionReasonDescription;

		return $this;
	}

	public function withAccountDocumentCounty($accountDocumentCounty)
	{
		$this->accountDocumentCounty = $accountDocumentCounty;

		return $this;
	}

	public function withAccountDocumentBucharestCity($accountDocumentBucharestCity)
	{
		$this->accountDocumentBucharestCity = $accountDocumentBucharestCity;

		return $this;
	}

	public function withClientDocumentCounty($clientDocumentCounty)
	{
		$this->clientDocumentCounty = $clientDocumentCounty;

		return $this;
	}

	public function withClientDocumentBucharestCity($clientDocumentBucharestCity)
	{
		$this->clientDocumentBucharestCity = $clientDocumentBucharestCity;

		return $this;
	}

	public function withContractReference($contractReference)
	{
		$this->contractReference = $contractReference;

		return $this;
	}

	public function withOrderReference($orderReference)
	{
		$this->orderReference = $orderReference;

		return $this;
	}

	public function withConvertToDomesticCurrency($convertToDomesticCurrency)
	{
		$this->convertToDomesticCurrency = $convertToDomesticCurrency;

		return $this;
	}

	/**
	 * @param EFacturaDocumentClassificationCodeAttribute|array{documentPositionId: string, schemeIdentifier: string, classificationCode: string} $item
	 *
	 * @return $this
	 */
	public function addDocumentClassificationItem(array|EFacturaDocumentClassificationCodeAttribute $item): self
	{
		if(is_array($item)) {
			$item = EFacturaDocumentClassificationCodeAttribute::fromArray($item);
		}

		$this->efacturaClassificationCodeAttributes[] = $item;

		return $this;
	}


	/**
	 * @param EFacturaDocumentPositionUnitsAttribute|array{documentPositionUnits: string, documentPositionUnitsCode: string} $item
	 *
	 * @return $this
	 */
	public function addDocumentPositionUnitsAttribute(array|EFacturaDocumentPositionUnitsAttribute $item): self
	{
		if(is_array($item)) {
			$item = EFacturaDocumentPositionUnitsAttribute::fromArray($item);
		}

		$this->efacturaDocumentPositionUnitsAttributes[] = $item;

		return $this;
	}


}
