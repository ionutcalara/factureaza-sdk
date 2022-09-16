<?php

declare(strict_types=1);

/**
 * Contains the FactureazaClientTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-16
 *
 */

namespace Konekt\Factureaza\Tests;

use Konekt\Factureaza\Factureaza;
use PHPUnit\Framework\TestCase;

class FactureazaClientTest extends TestCase
{
    /** @test */
    public function the_api_client_can_be_instantiated()
    {
        $api = new Factureaza('');

        $this->assertInstanceOf(Factureaza::class, $api);
    }

    /** @test */
    public function it_can_be_created_with_the_connect_static_factory_method()
    {
        $api = Factureaza::connect('apikey');
        $this->assertInstanceOf(Factureaza::class, $api);
    }

    /** @test */
    public function a_sandbox_client_can_be_created_without_api_key_using_the_sandbox_static_factory_method()
    {
        $api = Factureaza::sandbox();
        $this->assertInstanceOf(Factureaza::class, $api);
    }

    /** @test */
    public function the_time_zone_is_europe_bucharest_by_default()
    {
        $api = Factureaza::sandbox();
        $this->assertEquals('Europe/Bucharest', $api->timezone()->getName());
    }

    /** @test */
    public function timezone_can_be_changed_to_utc()
    {
        $api = Factureaza::sandbox();
        $api->useUTCTime();
        $this->assertEquals('UTC', $api->timezone()->getName());
    }
}
