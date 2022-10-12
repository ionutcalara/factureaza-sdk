<?php

declare(strict_types=1);

/**
 * Contains the GetInvoiceAsPdf class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-12
 *
 */

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Contracts\Query;

class GetInvoiceAsPdf implements Query
{
    public function fields(): array
    {
        return ['id', 'pdfContent'];
    }

    public function __construct(
        private readonly string $id,
    ) {
    }

    public function resource(): string
    {
        return 'invoices';
    }

    public function arguments(): ?array
    {
        return ['id' => $this->id];
    }
}
