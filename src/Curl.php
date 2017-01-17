<?php

/**
 * Methods for transmitting via cURL.
 */
class Curl
{
    private static function postJson($url, $json)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json))
        );

        $result = curl_exec($ch);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (substr($code, 0, 1) != 2) {
            throw new Exception("Curl received response {$code}.");
        }

        curl_close($ch);

        return $result;
    }

}
