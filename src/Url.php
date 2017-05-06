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
        if (substr($url, 0, 4) != 'http') {
            $url = 'http://' . $url;    
        }

        $host = parse_url($url, PHP_URL_HOST);

        if ($stripWWW) {
            $host = str_replace('www.', '', $host);
        }

        return $host;
    }

    /**
     * Remove the query string from a URL.
     *
     * @param  string $url The URL including a query string
     * @return string
     */
    public static function removeQueryString($url)
    {
        return strtok($url, '?');
    }

    /**
     * Remove a query parameter from a URL. The entire query string will be removed
     * when there is only one parameter.
     *
     * @param  string $url The URL including a query string
     * @param  string $key The parameter name
     * @return string
     */
    public static function removeQueryParameter($url, $key)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $params);

        unset($params[$key]);

        if ($query = http_build_query($params)) {
            return strtok($url, '?') . '?' . $query;
        } else {
            return strtok($url, '?');
        }
    }

    /**
     * Add or update a query parameter in a URL.
     *
     * @param string $url   The URL including a query string
     * @param string $key   The parameter name
     * @param string $value The new value for the parameter
     * @return string
     */
    public static function addQueryParameter($url, $key, $value)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $params);

        $params[$key] = $value;

        return strtok($url, '?') . '?' . http_build_query($params);
    }

    /**
     * Checks if a URL is absolute (begins with a protocol).
     *
     * @param  string $url URL to check for protocol existence
     * @return bool true if the given string begins with a protocol
     */
    public static function isAbsoluteUrl($url)
    {
        return (bool) parse_url($url, PHP_URL_SCHEME);
    }

    /**
     * Checks if a string is a relative URL.
     *
     * @param  string $url URL to check for relativity
     * @return bool true if the given string begins with a forward slash
     */
    public static function isRelativeUrl($url)
    {
        return substr($url, 0, 1) == '/';
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
