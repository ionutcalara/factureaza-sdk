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
use Konekt\Factureaza\Requests\CreateInvoice;

trait Invoices
{
    public function createInvoice(CreateInvoice $invoice): Invoice
    {
    }
}
