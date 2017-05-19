<?php

/**
 * Parse URLs from text.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class UrlParser
{
    public static function getUrls($text, $filesOnly = false)
    {
        $urls = self::parseFromText($text);

        if ($filesOnly) {
            foreach ($urls as $k => $url) {
                if (!self::isFileUrl($url)) {
                    unset($urls[$k]);
                }
            }
        }

        return self::unique($urls);
    }

    private static function parseFromText($text)
    {
        $urls = array();

        // Arbitrary URLs
        preg_match_all('@(https?://([-\w\.]+)+(:\d+)?(/([\w-/_\.]*(\?\S+)?)?)?)@', $text, $matches);
        $urls = array_merge($urls, $matches[1]);
        foreach ($urls as $key => $url) {
            $urls[$key] = trim($url, "'\";#>.)");
        }

        // From HTML attributes
        preg_match_all('/(href|src|poster)=["\'](.*?)["\']/si', $text, $matches);
        $urls = array_merge($urls, $matches[2]);

        return self::unique($urls);
    }

    private static function isFileUrl($string)
    {
        $path = parse_url($string, PHP_URL_PATH);

        return (bool) pathinfo($path, PATHINFO_EXTENSION);
    }

    private static function unique($arr)
    {
        $arr = array_filter($arr);
        $arr = array_unique($arr);
        $arr = array_values($arr);

        return $arr;
    }

}
