<?php

/**
 * Methods for processing colors.
 */
class Color
{
    /**
     * Get the luminance (brightness) of a color, from 0 to 255.
     * https://en.wikipedia.org/wiki/Relative_luminance
     */
    public static function luminance($r, $g, $b)
    {
        return round((0.2126 * $r) + (0.7152 * $g) + (0.0722 * $b));
    }

    /**
     * Get the luminance of a color, simply as "light" or "dark".
     */
    public static function luminanceType($r, $g, $b)
    {
        $l = self::luminance($r, $g, $b);

        return ($l > 127) ? 'light' : 'dark';
    }

    /**
     * Get the saturation (vividness, purity) of a color, as a value from 0 to 100.
     */
    public static function saturation($r, $g, $b)
    {
        $min = min($r, $g, $b);
        $max = max($r, $g, $b);

        return $max == 0 ? 0 : (($max - $min) / $max) * 100;
    }

}
