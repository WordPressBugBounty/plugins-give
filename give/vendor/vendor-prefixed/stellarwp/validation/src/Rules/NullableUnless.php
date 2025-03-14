<?php

declare(strict_types=1);

namespace Give\Vendors\StellarWP\Validation\Rules;

use Closure;
use Give\Vendors\StellarWP\Validation\Commands\SkipValidationRules;
use Give\Vendors\StellarWP\Validation\Rules\Abstracts\ConditionalRule;

/**
 * The value is nullable unless the conditions pass.
 *
 * @since 1.2.0
 */
class NullableUnless extends ConditionalRule
{
    /**
     * {@inheritDoc}
     *
     * @since 1.2.0
     */
    public static function id(): string
    {
        return 'nullableUnless';
    }

    /**
     * {@inheritDoc}
     *
     * @since 1.2.0
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if ($value === null && $this->conditions->fails($values)) {
            return new SkipValidationRules();
        }
    }
}
