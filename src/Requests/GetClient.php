<?php

declare(strict_types=1);

/**
 * Contains the GetClient class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-08
 *
 */

namespace Konekt\Factureaza\Requests;

use Konekt\Factureaza\Contracts\Query;
use Konekt\Factureaza\Requests\Concerns\RequestsClientFields;

class GetClient implements Query
{
    use RequestsClientFields;

    public function __construct(
        private readonly string $id,
    ) {
    }

    public function resource(): string
    {
        return 'clients';
    }

    public function arguments(): ?array
    {
        return ['id' => $this->id];
    }
}
