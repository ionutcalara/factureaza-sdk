<?php

declare(strict_types=1);

/**
 * Contains the GraphQLMutation interface.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-06
 *
 */

namespace Konekt\Factureaza\Contracts;

interface Mutation extends GraphQLRequest
{
    public function operation(): string;

    public function payload(): array;
}
