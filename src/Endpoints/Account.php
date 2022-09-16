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

trait Account
{
    public function myAccount(): \Konekt\Factureaza\Models\Account
    {
        $response = $this->query('account', ['id', 'name', 'companyName']);

        $account = $response->json('data')['account'][0] ?? [];

        return new \Konekt\Factureaza\Models\Account(
            $account['id'],
            $account['name'],
            $account['companyName'],
        );
    }
}
