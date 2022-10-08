<?php

declare(strict_types=1);

/**
 * Contains the GraphQLOperation class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-08
 *
 */

namespace Konekt\Factureaza\Models;

use Konekt\Enum\Enum;

/**
 * @method static GraphQLOperation QUERY()
 * @method static GraphQLOperation MUTATION()
 *
 * @method bool isQuery()
 * @method bool isMutation()
 *
 * @property-read bool $is_query
 * @property-read bool $is_mutation
 */
class GraphQLOperation extends Enum
{
    public const __DEFAULT = self::QUERY;
    public const QUERY = 'query';
    public const MUTATION = 'mutation';
}
