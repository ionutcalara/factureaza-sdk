<?php

declare(strict_types=1);

/**
 * Contains the Account trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-16
 *
 */

namespace Konekt\Factureaza\Endpoints;

use Konekt\Factureaza\Models\MyAccount;

trait Account
{
    private static array $accountQueryFields = [
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

    public function myAccount(): MyAccount
    {
        $response = $this->query('account', self::$accountQueryFields);

        return new MyAccount(
            $this->remap(
                $response->json('data')['account'][0] ?? [],
                MyAccount::class,
            )
        );
    }
}
