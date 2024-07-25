<?php

declare(strict_types=1);

/**
 * Contains the InvoiceTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-17
 *
 */

namespace Konekt\Factureaza\Tests\Integration;

use Konekt\Factureaza\Factureaza;
use Konekt\Factureaza\Models\Invoice;
use Konekt\Factureaza\Models\InvoiceItem;
use Konekt\Factureaza\Models\PaymentType;
use Konekt\Factureaza\Requests\CreateInvoice;
use Konekt\Factureaza\Requests\CreatePayment;
use Konekt\Factureaza\Requests\ExportInvoiceAsEFactura;
use Konekt\Factureaza\Requests\SubmitInvoiceToAnafAsEFactura;
use Konekt\Factureaza\Requests\UpdateInvoice;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
	public const INVOICE_SERIES = '1061104610';

	public const CLIENT_ID = '1064116438';

	public const INVOICE_DATE = '2024-04-26';

	/** @test */
	public function it_can_create_an_invoice_in_the_sandbox_environment()
	{
		$api = Factureaza::sandbox();

		$invoice = $this->createInvoice($api);

		$this->assertInstanceOf(Invoice::class, $invoice);
		$this->assertEquals(self::INVOICE_DATE, $invoice->documentDate->format('Y-m-d'));
		$this->assertEquals(self::CLIENT_ID, $invoice->clientId);
		$this->assertEquals(19, $invoice->total);
		$this->assertEquals('RON', $invoice->currency);
		$this->assertIsString($invoice->number);
		$this->assertIsString($invoice->hashcode);
		$this->assertEquals('Hello I am on the top', $invoice->upperAnnotation);
		$this->assertEquals('Hello I smell the bottom', $invoice->lowerAnnotation);

		$this->assertCount(1, $invoice->items);

		$item = $invoice->items[0];
		$this->assertInstanceOf(InvoiceItem::class, $item);
		$this->assertEquals('Service', $item->description);
		$this->assertEquals(19, $item->price);
		$this->assertEquals('luna', $item->unit);
		$this->assertEquals('', $item->productCode);
		$this->assertEquals(1, $item->quantity);

		$request = CreatePayment::forInvoice($invoice->id)
			->withPaymentDate(self::INVOICE_DATE)
			->withAmount('19')
			->withPaymentType(PaymentType::CARD());

		$payment = $api->createPayment($request);

		$this->assertEquals('19', $payment->amount);
	}

	/** @test */
	public function it_can_update_an_invoice_in_the_sandbox_environment()
	{
		$api = Factureaza::sandbox();

		$invoice = $this->createInvoice($api);

		$updateInvoice = UpdateInvoice::fromArray([
			'id' => $invoice->id,
			'clientState' => 'B',
			'clientIsCompany' => true
		])->itemsFromOriginal($invoice);


		$invoice = $api->updateInvoice($updateInvoice);

		$this->assertInstanceOf(Invoice::class, $invoice);
		$this->assertEquals('B', $invoice->clientState);
		$this->assertEquals(true, $invoice->clientIsCompany);
		$this->assertEquals('1064116434', $invoice->clientId);
	}

	/** @test */
	public function it_can_retrieve_invoices_as_pdf_in_base64_format()
	{
		$api = Factureaza::sandbox();

		$invoice = $this->createInvoice($api);

        $pdf = Factureaza::sandbox()->invoiceAsPdfBase64($invoice->id);
        $this->assertIsString($pdf);
        $this->assertStringStartsWith('%PDF', base64_decode($pdf));
    }

	/** @test */
	public function it_can_retrieve_an_invoice_by_id()
	{
		$api = Factureaza::sandbox();

		$invoice = $this->createInvoice($api);
		$invoice = Factureaza::sandbox()->invoice($invoice->id);

		$this->assertInstanceOf(Invoice::class, $invoice);
		$this->assertEquals(self::INVOICE_DATE, $invoice->documentDate->format('Y-m-d'));
		$this->assertEquals(self::CLIENT_ID, $invoice->clientId);
		$this->assertEquals(19, $invoice->total);
		$this->assertEquals('RON', $invoice->currency);
		$this->assertEquals('Hello I am on the top', $invoice->upperAnnotation);
		$this->assertEquals('Hello I smell the bottom', $invoice->lowerAnnotation);

		$this->assertCount(1, $invoice->items);

		$item = $invoice->items[0];
		$this->assertInstanceOf(InvoiceItem::class, $item);
		$this->assertEquals('Service', $item->description);
		$this->assertEquals(19, $item->price);
		$this->assertEquals('luna', $item->unit);
		$this->assertEquals(1, $item->quantity);
	}

	/** @test */
	public function a_newly_created_invoice_is_open_by_default()
	{
		$api = Factureaza::sandbox();

		$invoice = $this->createInvoice($api);

		$this->assertTrue($invoice->state->isOpen(), 'The invoice is not in open state by default');
	}

	/** @test */
	public function a_draft_invoice_can_be_explicitly_requested()
	{
		$api = Factureaza::sandbox();

		$request = CreateInvoice::inSeries(self::INVOICE_SERIES)
			->forClient(self::CLIENT_ID)
			->withEmissionDate(self::INVOICE_DATE)
			->asDraft()
			->addItem(['description' => 'Service', 'price' => 19, 'unit' => 'luna', 'productCode' => '']);

		$invoice = $api->createInvoice($request);

		$this->assertTrue($invoice->state->isDraft(), 'The invoice should be a draft when explicitly requested');
	}

	/** @test */
	public function a_invoice_can_be_exported_as_einvoice()
	{
		$api = Factureaza::sandbox();

		$request = CreateInvoice::inSeries(self::INVOICE_SERIES)
			->forClient(self::CLIENT_ID)
			->withEmissionDate(self::INVOICE_DATE)
			->asClosed()
			->addItem(['description' => 'Service', 'price' => 19, 'unit' => 'luna', 'productCode' => '']);
		$invoice = $api->createInvoice($request);

		$exportInvoiceAsEFactura = ExportInvoiceAsEFactura::forInvoice($invoice->id)
			->addDocumentPositionUnitsAttribute([
				'documentPositionUnits'=> 'luna',
				'documentPositionDescriptions'=> 'M36'
			])
			->withPaymentMethodDebitCard();

		$xml = $api->invoiceAsEFactura($exportInvoiceAsEFactura);

		$this->assertNotEmpty($xml, 'The invoice efacturaXml should be populated');
		$this->assertStringContainsString('<cbc:PaymentMeansCode>55</cbc:PaymentMeansCode>', $xml);
	}

	/** @test */
	public function a_invoice_can_be_sent_to_anaf_as_einvoice()
	{
		$api = Factureaza::sandbox();

		$request = CreateInvoice::inSeries(self::INVOICE_SERIES)
			->forClient(self::CLIENT_ID)
			->withEmissionDate(self::INVOICE_DATE)
			->asClosed()
			->addItem(['description' => 'Service', 'price' => 19, 'unit' => 'luna', 'productCode' => '']);
		$invoice = $api->createInvoice($request);

		$exportInvoiceAsEFactura = SubmitInvoiceToAnafAsEFactura::forInvoice($invoice->id)
			->addDocumentPositionUnitsAttribute([
				'documentPositionUnits'=> 'luna',
				'documentPositionDescriptions'=> 'M36'
			])
			->withPaymentMethodDebitCard();

		$status = $api->submitInvoiceToAnafAsEFactura($exportInvoiceAsEFactura);

		$this->assertNull($status, 'The service should not throw errors, testing the endpoint doesnt work in sandbox');
	}

    /** @test */
    public function can_get_a_list_of_invoices_for_client()
    {
        $api = Factureaza::sandbox();

        $request = CreateInvoice::inSeries(self::INVOICE_SERIES)
            ->forClient(self::CLIENT_ID)
            ->withEmissionDate(self::INVOICE_DATE)
            ->asClosed()
            ->addItem(['description' => 'Service', 'price' => 19, 'unit' => 'luna', 'productCode' => '']);
        $invoice1 = $api->createInvoice($request);

        sleep(1); // To make sure the dates are different (we don't have milliseconds in the date format

        $request2 = CreateInvoice::inSeries(self::INVOICE_SERIES)
            ->forClient(self::CLIENT_ID)
            ->withEmissionDate(self::INVOICE_DATE)
            ->asClosed()
            ->addItem(['description' => 'Service 2', 'price' => 20, 'unit' => 'luna', 'productCode' => '']);
        $invoice2 = $api->createInvoice($request2);


        $invoices = Factureaza::sandbox()->invoicesByClientId(self::CLIENT_ID);
        $invoice = $invoices[0];

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(self::INVOICE_DATE, $invoice->documentDate->format('Y-m-d'));
        $this->assertEquals(self::CLIENT_ID, $invoice->clientId);
        $this->assertEquals($invoice2->number, $invoice->number);
        $this->assertEquals(20, $invoice->total);

        $this->assertCount(1, $invoice->items);

        $item = $invoice->items[0];
        $this->assertInstanceOf(InvoiceItem::class, $item);
        $this->assertEquals('Service 2', $item->description);
        $this->assertEquals(20, $item->price);
        $this->assertEquals('luna', $item->unit);
        $this->assertEquals(1, $item->quantity);


        $invoice = $invoices[1];
        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(self::INVOICE_DATE, $invoice->documentDate->format('Y-m-d'));
        $this->assertEquals(self::CLIENT_ID, $invoice->clientId);
        $this->assertEquals(19, $invoice->total);
        $this->assertEquals($invoice1->number, $invoice->number);

        $this->assertCount(1, $invoice->items);

        $item = $invoice->items[0];
        $this->assertInstanceOf(InvoiceItem::class, $item);
        $this->assertEquals('Service', $item->description);
        $this->assertEquals(19, $item->price);
        $this->assertEquals('luna', $item->unit);
        $this->assertEquals(1, $item->quantity);
    }

	private function createInvoice(Factureaza $api): Invoice
	{
		$request = CreateInvoice::inSeries(self::INVOICE_SERIES)
			->forClient(self::CLIENT_ID)
			->withEmissionDate(self::INVOICE_DATE)
			->withUpperAnnotation('Hello I am on the top')
			->withLowerAnnotation('Hello I smell the bottom')
//            ->withCompanyReferenceCodeSource('company_vat_id')
			->addItem(['description' => 'Service', 'price' => 19, 'unit' => 'luna', 'productCode' => '']);

		return $api->createInvoice($request);
	}


}
