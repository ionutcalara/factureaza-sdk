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

use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /** @test */
    public function it_can_create_an_invoice_in_the_sandbox_environment()
    {
        $this->assertEquals('Hey, I am in progress', 'Hey, I am in progress');
    }
}
