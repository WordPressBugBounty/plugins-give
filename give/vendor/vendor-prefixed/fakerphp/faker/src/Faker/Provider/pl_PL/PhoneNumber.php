<?php
/**
 * @license MIT
 *
 * Modified by impress-org on 22-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Give\Vendors\Faker\Provider\pl_PL;

class PhoneNumber extends \Give\Vendors\Faker\Provider\PhoneNumber
{
    protected static $formats = [
        '+48 ## ### ## ##',
        '0048 ## ### ## ##',
        '### ### ###',
        '+48 ### ### ###',
        '0048 ### ### ###',
        '#########',
        '(##) ### ## ##',
        '+48(##)#######',
        '0048(##)#######',
    ];
}
