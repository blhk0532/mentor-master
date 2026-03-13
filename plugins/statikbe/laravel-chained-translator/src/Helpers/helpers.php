<?php

declare(strict_types=1);

use Illuminate\Support\Arr;

if (! function_exists('array_undot')) {
    /**
     * Expands a list with keys with dots into a hierarchical list.
     */
    function array_undot(array $dotNotationArray): array
    {
        $array = [];
        foreach ($dotNotationArray as $key => $value) {
            // if there is a space after the dot, this could legitimately be
            // a single key and not nested.
            if (count(explode('. ', $key)) > 1) {
                $array[$key] = $value;
            } else {
                Arr::set($array, $key, $value);
            }
        }

        return $array;
    }
}
