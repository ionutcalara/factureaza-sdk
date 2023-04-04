<?php

declare(strict_types=1);

/**
 * Contains the RequestsInvoiceFields trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-12
 *
 */

namespace Konekt\Factureaza\Requests\Concerns;

trait RequestsPaymentFields
{
    private static array $queryFields = [
        'id',
        'paymentDate',
        'paymentType',
        'invoiceId',
        'description',
        'currency',
        'amount',
        'createdAt',
        'updatedAt',
    ];

    public function fields(): array
    {
        return self::$queryFields;
    }
}
