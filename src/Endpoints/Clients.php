<?php

declare(strict_types=1);

/**
 * Contains the Clients trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-08
 *
 */

namespace Konekt\Factureaza\Endpoints;

use Konekt\Factureaza\Models\Client;
use Konekt\Factureaza\Requests\CreateClient;
use Konekt\Factureaza\Requests\GetClient;
use Konekt\Factureaza\Requests\GetClientByTaxNo;

trait Clients
{
    public function client(string $id): ?Client
    {
        $response = $this->query(new GetClient($id));

        return new Client(
            $this->remap($response->json('data')['clients'][0] ?? [], Client::class)
        );
    }

    public function clientByTaxNo(string $taxNo): ?Client
    {
        $response = $this->query(new GetClientByTaxNo($taxNo));

        return new Client(
            $this->remap($response->json('data')['clients'][0] ?? [], Client::class)
        );
    }

    public function createClient(array|CreateClient $client): Client
    {
        $request = is_array($client) ? CreateClient::fromArray($client) : $client;
        $response = $this->mutate($request);

        return new Client(
            $this->remap($response->json('data')['createClient'], Client::class)
        );
    }
}
