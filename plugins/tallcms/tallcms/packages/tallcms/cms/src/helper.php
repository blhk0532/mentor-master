<?php

/**
 * TallCMS Pro Helper Functions
 */
if (! function_exists('tallcms_pro_parse_highlight_lines')) {
    /**
     * Parse highlight lines string into array of line numbers
     * Supports: "1,3,5" and ranges "1-5,10,15-20"
     */
    function tallcms_pro_parse_highlight_lines(?string $input): array
    {
        if (empty($input)) {
            return [];
        }

        $lines = [];
        $parts = explode(',', $input);

        foreach ($parts as $part) {
            $part = trim($part);
            if (str_contains($part, '-')) {
                // Range: "3-7"
                [$start, $end] = explode('-', $part, 2);
                $start = (int) trim($start);
                $end = (int) trim($end);
                if ($start > 0 && $end >= $start) {
                    for ($i = $start; $i <= $end; $i++) {
                        $lines[] = $i;
                    }
                }
            } else {
                // Single line number
                $lineNum = (int) $part;
                if ($lineNum > 0) {
                    $lines[] = $lineNum;
                }
            }
        }

        return array_unique($lines);
    }
}

if (! function_exists('tallcms_pro_abbreviate_number')) {
    /**
     * Abbreviate large numbers (1000 -> 1K, 1000000 -> 1M)
     */
    function tallcms_pro_abbreviate_number(float $num): string
    {
        if ($num >= 1000000) {
            return round($num / 1000000, 1).'M';
        }
        if ($num >= 1000) {
            return round($num / 1000, 1).'K';
        }

        return number_format($num, 0);
    }
}
