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
use Konekt\Factureaza\Requests\UpdateInvoice;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /** @test */
    public function it_can_create_an_invoice_in_the_sandbox_environment()
    {
        $api = Factureaza::sandbox();

	    $invoice = $this->createInvoice($api);

	    $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('2021-09-17', $invoice->documentDate->format('Y-m-d'));
        $this->assertEquals('1064116434', $invoice->clientId);
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
			->withPaymentDate('2021-09-17')
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
			 'clientState'=> 'B',
			 'clientIsCompany' => true
		 ])->itemsFromOriginal($invoice);



		 $invoice = $api->updateInvoice($updateInvoice);

		$this->assertInstanceOf(Invoice::class, $invoice);
		$this->assertEquals('B', $invoice->clientState);
		$this->assertEquals( true, $invoice->clientIsCompany);
		$this->assertEquals('1064116434', $invoice->clientId);
	}

    /** @test */
    public function it_can_retrieve_invoices_as_pdf_in_base64_format()
    {
        $pdf = Factureaza::sandbox()->invoiceAsPdfBase64('1065254039');
        $this->assertIsString($pdf);
        $this->assertStringStartsWith('%PDF', base64_decode($pdf));
    }

    /** @test */
    public function it_can_retrieve_an_invoice_by_id()
    {
        $invoice = Factureaza::sandbox()->invoice('1065254039');

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('2020-02-15', $invoice->documentDate->format('Y-m-d'));
        $this->assertEquals('1064116436', $invoice->clientId);
        $this->assertEquals(5706.05, $invoice->total);
        $this->assertEquals('RON', $invoice->currency);
        $this->assertEquals('openapi-6', $invoice->number);
        $this->assertEquals('a6ea63aa-7d61-11ea-86a3-63919243', $invoice->hashcode);
        $this->assertNull($invoice->upperAnnotation);
        $this->assertEquals("Sperăm intr-o colaborare fructuoasă şi pe viitor.\n Cu stimă maximă și virtute absolută, Ion Pop S.C. DEMO IMPEX S.R.L.", $invoice->lowerAnnotation);

        $this->assertCount(2, $invoice->items);

        $item = $invoice->items[0];
        $this->assertInstanceOf(InvoiceItem::class, $item);
        $this->assertEquals('Prestări servicii programare cf. ctc. 3482/14.03.2020', $item->description);
        $this->assertEquals(191, $item->price);
        $this->assertEquals('ore', $item->unit);
        $this->assertNull($item->productCode);
        $this->assertEquals(21, $item->quantity);
    }

    /** @test */
    public function a_newly_created_invoice_is_open_by_default()
    {
        $api = Factureaza::sandbox();

        $request = CreateInvoice::inSeries('1061104148')
            ->forClient('1064116434')
            ->withEmissionDate('2021-09-17')
            ->addItem(['description' => 'Service', 'price' => 19, 'unit' => 'luna', 'productCode' => '']);

        $invoice = $api->createInvoice($request);

        $this->assertTrue($invoice->state->isOpen(), 'The invoice is not in open state by default');
    }

    /** @test */
    public function a_draft_invoice_can_be_explicitly_requested()
    {
        $api = Factureaza::sandbox();

        $request = CreateInvoice::inSeries('1061104148')
            ->forClient('1064116434')
            ->withEmissionDate('2021-09-17')
            ->asDraft()
            ->addItem(['description' => 'Service', 'price' => 19, 'unit' => 'luna', 'productCode' => '']);

        $invoice = $api->createInvoice($request);

        $this->assertTrue($invoice->state->isDraft(), 'The invoice should be a draft when explicitly requested');
    }

	private function createInvoice(Factureaza $api): Invoice
	{
		$request = CreateInvoice::inSeries('1061104148')
			->forClient('1064116434')
			->withEmissionDate('2021-09-17')
			->withUpperAnnotation('Hello I am on the top')
			->withLowerAnnotation('Hello I smell the bottom')
			->addItem(['description' => 'Service', 'price' => 19, 'unit' => 'luna', 'productCode' => '']);

		return $api->createInvoice($request);
	}
}
