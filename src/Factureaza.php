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

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeZone;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Konekt\Factureaza\Contracts\Mutation;
use Konekt\Factureaza\Contracts\Query;
use Konekt\Factureaza\Contracts\Resource;
use Konekt\Factureaza\Exceptions\FactureazaException;
use ReflectionNamedType;

final class Factureaza
{
    use Endpoints\Account;
    use Endpoints\Invoices;

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

    protected function request(string $operation, string $resource, array $fields): Response
    {
        $response = $this->http->withBasicAuth($this->apiKey, '')
            ->asJson()
            ->post(
                $this->endpoint,
                [
                    'query' => "{ $resource { " . implode(' ', $fields) . ' } }',
                ],
            );
        $this->checkForErrors($response);

        return $response;
    }

    protected function query(Query $query): Response
    {
        return $this->request('query', $query->resource(), $query->fields());
    }

    protected function mutate(Mutation $mutation): Response
    {
        $response = $this->http->withBasicAuth($this->apiKey, '')
            ->asJson()
            ->post(
                $this->endpoint,
                [
                    "query" => "mutation { {$mutation->operation()} (\n"
                        . $this->toGraphQLPayload($mutation->payload())
                        . ") {\n   " . implode("\n   ", $mutation->fields())
                        . "\n  }\n}",
                ],
            );
        $this->checkForErrors($response);

        return $response;
    }

    private function remap(array $attributes, string $forClass): array
    {
        if (!in_array(Resource::class, class_implements($forClass))) {
            throw new \LogicException("The $forClass class must implement the " . Resource::class . ' interface');
        }

        $map = $forClass::attributeMap();
        $result = [];

        foreach ($attributes as $key => $value) {
            $actualKey = array_key_exists($key, $map) ? $map[$key] : $key;
            if (is_array($actualKey)) {
                $actualValue = call_user_func($actualKey[1], $value);
                $actualKey = $actualKey[0];
            } else {
                $actualValue = $this->isADateTimeProperty($actualKey, $forClass) ? $this->makeDateTime($value) : $value;
            }

            $result[$actualKey] = $actualValue;
        }

        return $result;
    }

    private function isADateTimeProperty(string $property, string $class): bool
    {
        if (!property_exists($class, $property)) {
            return false;
        }

        $dateTypes = [\DateTime::class, \DateTimeImmutable::class, Carbon::class, CarbonImmutable::class];
        $details = new \ReflectionProperty($class, $property);

        if ($details->getType() instanceof ReflectionNamedType) {
            return in_array($details->getType()->getName(), $dateTypes);
        }

        return !empty(Arr::where($details->getType()->getTypes(), fn ($type) => in_array($type, $dateTypes)));
    }

    private function makeDateTime(string $value): CarbonImmutable
    {
        return CarbonImmutable::parse($value)->setTimezone($this->timezone);
    }

    private function checkForErrors(Response $response): void
    {
        if (null === $errors = $response->json('errors')) {
            return;
        }

        throw new FactureazaException($errors[0]['message']);
    }

    private function toGraphQLPayload(array $payload): string
    {
        $result = '';
        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                if (Arr::isList($value)) {
                    $result .= "  $key: [\n" . $this->toGraphQLPayload($value) . "]\n";
                } else {
                    $result .= "  {\n" . $this->toGraphQLPayload($value) . "}\n";
                }
            } else {
                $result .= sprintf("  $key: %s\n", json_encode($value));
            }
        }

        return $result;
    }
}
