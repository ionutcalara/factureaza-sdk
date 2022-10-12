<?php

declare(strict_types=1);

/**
 * Contains the DocumentState class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-12
 *
 */

namespace Konekt\Factureaza\Models;

use Konekt\Enum\Enum;

/**
 * @method static DocumentState DRAFT()
 * @method static DocumentState OPEN()
 * @method static DocumentState CLOSED()
 * @method static DocumentState CANCELLED()
 *
 * @method bool isDraft()
 * @method bool isOpen()
 * @method bool isClosed()
 * @method bool isCancelled()
 *
 * @property-read bool $is_draft
 * @property-read bool $is_open
 * @property-read bool $is_closed
 * @property-read bool $is_cancelled
 */
class DocumentState extends Enum
{
    public const __DEFAULT = self::OPEN;

    public const DRAFT = 'draft';
    public const OPEN = 'open';
    public const CLOSED = 'closed';
    public const CANCELLED = 'cancelled';
}
