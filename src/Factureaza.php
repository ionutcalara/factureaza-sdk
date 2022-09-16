<?php

declare(strict_types=1);

/**
 * Contains the Factureaza class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-09-16
 *
 */

namespace Konekt\Factureaza;

use DateTimeZone;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Client\Response;

final class Factureaza
{
    use Endpoints\Account;

    private const SANDBOX_URL = 'https://sandbox.factureaza.ro/graphql';
    private const SANDBOX_KEY = '72543f4dc00474bc40a27916d172eb93339fae894ec7a6f2dceb4751d965';

    private string $endpoint = 'https://factureaza.ro/graphql';

    private DateTimeZone $timezone;

    private HttpClient $http;

    public function __construct(
        private string $apiKey
    ) {
        $this->timezone = new DateTimeZone('Europe/Bucharest');
        $this->http = new HttpClient();
    }

    public static function connect(string $apiKey): Factureaza
    {
        return new Factureaza($apiKey);
    }

    public static function sandbox(): Factureaza
    {
        $instance = new Factureaza(self::SANDBOX_KEY);
        $instance->endpoint = self::SANDBOX_URL;

        return $instance;
    }

    public function useUTCTime(): self
    {
        $this->timezone = new DateTimeZone('UTC');

        return $this;
    }

    public function timezone(): DateTimeZone
    {
        return $this->timezone;
    }

    protected function query(string $resource, array $fields): Response
    {
        return $this->http->withBasicAuth($this->apiKey, '')
            ->asJson()
            ->post(
                $this->endpoint,
                [
                    'query' => "{ $resource { " . implode(' ', $fields) . ' } }',
                ],
            );
    }
}
