<?php

declare(strict_types=1);

/**
 * Contains the ValidationResult class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-10-06
 *
 */

namespace Konekt\Factureaza\Exceptions;

use Konekt\Enum\Enum;

/**
 * @method static ValidationResult FAILED()
 * @method static ValidationResult PASSED()
 *
 * @method bool isFailed()
 * @method bool isPassed()
 *
 * @property-read bool $is_failed
 * @property-read bool $is_passed
 */
class ValidationResult extends Enum
{
    public const FAILED = 'failed';
    public const PASSED = 'passed';
}
