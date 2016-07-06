<?php

/**
 * Printer
 *
 * Methods for printing/visualizing data.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Printer
{
    /**
     * Print an associative array as HTML.
     */
    public static function printArrayAsTable($array)
    {
        $data = '';
        foreach ($array as $k => $i) {
            $data .= '<tr>';
            $data .= '<td class="key">' . $k . '</td>';
            if ($k=='web_address') {
                $data .= '<td>' . self::makeLink($i, true) . '</td>';
            } else {
                $data .= '<td>' . $i . '</td>';
            }
            $data .= '</tr>';
        }

        $html  = '<table>';
        $html .= '<tbody>';
        $html .= $data;
        $html .= '</tbody>';
        $html .= '</table>' . "\n";

        echo $html;
    }

    /**
     * Create an HTML link with <a>
     */
    public static function makeLink($url, $newWindow = false)
    {
        if (preg_match('/^https?:/', $url)) {

            if ($newWindow) {
                $target = ' target="_blank"';
            } else {
                $target = '';
            }

            $html = "<a href=\"$url\"$target>$url</a>";
            $url = $html;
        }

        return $url;
    }
    
    /**
     * Print an array
     */
    public static function printArray(array $array)
    {
        foreach ($array as $i) {
            echo $i . "\n";
        }
    }

}
