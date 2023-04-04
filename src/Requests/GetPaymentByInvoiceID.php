<?php

declare(strict_types=1);

/**
 * Contains the GetClient class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-08
 *
 */

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Contracts\Query;
use Konekt\Factureaza\Requests\Concerns\RequestsClientFields;
use Konekt\Factureaza\Requests\Concerns\RequestsPaymentFields;

class GetPaymentByInvoiceID implements Query
{
    use RequestsPaymentFields;

    public function __construct(
        private readonly string $invoiceId,
    ) {
    }

    public function resource(): string
    {
        return 'payments';
    }

    public function arguments(): ?array
    {
        return ['invoiceId' => $this->invoiceId];
    }
}
