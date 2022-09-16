<?php

declare(strict_types=1);

/**
 * Contains the ApiClientTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-16
 *
 */

namespace Konekt\Factureaza\Tests;

use Konekt\Factureaza\ApiClient;
use PHPUnit\Framework\TestCase;

class ApiClientTest extends TestCase
{
    /** @test */
    public function the_api_client_can_be_instantiated()
    {
        $api = new ApiClient();

        $this->assertInstanceOf(ApiClient::class, $api);
    }
}
