<?php

declare(strict_types=1);

/**
 * Contains the GetInvoice class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-12
 *
 */

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Contracts\Query;
use Konekt\Factureaza\Requests\Concerns\RequestsInvoiceFields;

class GetInvoicesByClientID implements Query
{
    use RequestsInvoiceFields;

    public function __construct(
        private readonly string $id,
        private readonly string $orderBy,
        private readonly int    $limit,
        private readonly int    $offset,
    )
    {
    }

    public function resource(): string
    {
        return 'invoices';
    }

    public function arguments(): ?array
    {
        return [
            'clientId' => $this->id,
            'orderBy' => $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset
        ];
    }
}
