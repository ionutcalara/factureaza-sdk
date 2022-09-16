<?php

declare(strict_types=1);

/**
 * Contains the AccountTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-16
 *
 */

namespace Konekt\Factureaza\Tests\Integration;

use Konekt\Factureaza\Factureaza;
use Konekt\Factureaza\Models\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    /** @test */
    public function it_can_connect_to_the_sandbox()
    {
        $account = Factureaza::sandbox()->myAccount();

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('sandbox', $account->accountName);
        $this->assertEquals('Test Services SRL', $account->companyName);
        $this->assertEquals('340138083', $account->id);
    }
}
