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

trait RequestsInvoiceFields
{
    private static array $queryFields = [
        'id',
        'documentDate',
        'documentState',
        'clientId',
        'clientUid',
        'clientName',
        'clientAddress',
        'clientAddress2',
        'clientCity',
	    'clientState',
	    'clientZip',
        'currency',
        'total',
        'hashcode',
        'series',
        'lowerAnnotation',
        'upperAnnotation',
        'documentPositions { id price unit unitCount position description productCode total vat }',
        'createdAt',
        'updatedAt',
    ];

    public function fields(): array
    {
        return self::$queryFields;
    }
}
