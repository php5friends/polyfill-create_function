<?php

/*
Copying and distribution of this file, with or without modification,
are permitted in any medium without royalty provided the copyright
notice and this notice are preserved.  This file is offered as-is,
without any warranty.
*/

/**
 * Polyfill of create_function()
 *
 * @copylight 2020 Friends of PHP5
 * @author USAMI Kenta <tadsan@zonu.me>
 * @license FSFAP
 */
namespace Php5Friends
{
    /**
     * Create an anonymous (lambda-style) function, without error
     *
     * NOTICE: Do not input that passed from user request
     *
     * @see https://www.php.net/create_function
     * @param string $args
     * @param string $code
     * @phpstan-return callable-string
     * @psalm-return callable-string
     */
    function create_function($args, $code): string
    {
        if (PHP_MAJOR_VERSION <= 7) {
            return @\create_function($args, $code);
        }

        static $i;

        $namespace = __NAMESPACE__;

        do {
            $i++;
            $name = "lambda_{$i}";
        } while (\function_exists("{$namespace}\\{$name}"));

        eval("namespace {$namespace} { function {$name}({$args}) { {$code} } }");

        return __NAMESPACE__ . '\\' . $name;
    }
}

namespace {
    if (!function_exists('create_function')) {
        /**
         * @param string $args
         * @param string $code
         * @return string
         */
        function create_function($args, $code)
        {
            return Php5Friends\create_function($args, $code);
        }
    }
}
