<?php

declare(strict_types=1);

/**
 * Contains the GetMyAccount class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-08
 *
 */

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Contracts\Query;

class GetMyAccount implements Query
{
    private static array $queryFields = [
        'id',
        'name',
        'companyName',
        'companyAddress1',
        'companyAddress2',
        'companyZip',
        'companyCity',
        'companyState',
        'companyCountry { iso }',
        'companyRegistrationId',
        'companyEuid',
        'companyUid',
        'companyTaxId',
        'domesticCurrency',
        'createdAt',
        'updatedAt',
    ];

    public function fields(): array
    {
        return self::$queryFields;
    }

    public function resource(): string
    {
        return 'account';
    }

    public function arguments(): ?array
    {
        return null;
    }
}
