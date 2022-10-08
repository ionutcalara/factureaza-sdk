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
use Konekt\Factureaza\Requests\GetMyAccount;

trait Account
{


    public function myAccount(): MyAccount
    {
        $response = $this->query(new GetMyAccount());

        return new MyAccount(
            $this->remap(
                $response->json('data')['account'][0] ?? [],
                MyAccount::class,
            )
        );
    }
}
