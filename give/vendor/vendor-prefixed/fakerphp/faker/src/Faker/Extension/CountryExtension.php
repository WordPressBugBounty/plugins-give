<?php
/**
 * @license MIT
 *
 * Modified by impress-org on 22-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Give\Vendors\Faker\Extension;

/**
 * @experimental This interface is experimental and does not fall under our BC promise
 */
interface CountryExtension extends Extension
{
    /**
     * @example 'Japan'
     */
    public function country(): string;
}
