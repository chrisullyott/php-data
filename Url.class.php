<?php

/**
 * Methods for URLs.
 */
class Url
{
    /**
     * Check whether a string represents a URL.
     */
    public static function isUrl($string)
    {
        return (bool) filter_var($string, FILTER_VALIDATE_URL);
    }

    /**
     * Get the host from a URL.
     */
    public static function getHostFromUrl($url, $stripWWW = true)
    {
        $host = parse_url($url, PHP_URL_HOST);

        if ($stripWWW) {
            $host = str_replace('www.', '', $host);
        }

        return $host;
    }

    /**
     * Remove the query string from a URL.
     */
    public static function removeQueryString($url)
    {
        return preg_replace('/(\/[^?]+).*/', '$1', $url);
    }

    /**
     * Check whether a URL represents an existing resource.
     */
    public static function exists($url, $connectionTimeout = 5)
    {
        $c = curl_init($url);

        curl_setopt($c, CURLOPT_NOBODY, true);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, $connectionTimeout);
        curl_setopt($c, CURLOPT_TIMEOUT, $connectionTimeout);

        curl_exec($c);

        $responseCode = curl_getinfo($c, CURLINFO_HTTP_CODE);

        curl_close($c);

        return substr($responseCode, 0, 1) == 2;
    }
}
