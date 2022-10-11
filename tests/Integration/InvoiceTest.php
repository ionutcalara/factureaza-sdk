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
use Konekt\Factureaza\Requests\CreateInvoice;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /** @test */
    public function it_can_create_an_invoice_in_the_sandbox_environment()
    {
        $api = Factureaza::sandbox();

        $request = CreateInvoice::inSeries('1061104148')
            ->forClient('1064116434')
            ->withEmissionDate('2021-09-17')
            ->withUpperAnnotation('Hello I am on the top')
            ->withLowerAnnotation('Hello I smell the bottom')
            ->addItem(['description' => 'Service', 'price' => 19, 'unit' => 'luna', 'productCode' => '']);

        $invoice = $api->createInvoice($request);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('2021-09-17', $invoice->documentDate->format('Y-m-d'));
        $this->assertEquals('1064116434', $invoice->clientId);
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
    }
}
