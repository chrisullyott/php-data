<?php

/**
 * Methods for string generation and manipulation.
 */
class String
{
    /**
     * Convert a string into a slug.
     *
     * @param  string $str The string to slugify
     * @return string
     */
    public static function slug($str)
    {
        $str = strtolower($str);
        $str = preg_replace('/[\/_ ]+/', '-', $str);
        $str = preg_replace('/[^a-z0-9-]/', '', $str);

        return $str;
    }

    /**
     * Get a filtered array from a list of items using a common delimiter
     *
     * @param  string $list A list
     * @return array
     */
    public static function explodeList($list, $delimiter = ',')
    {
        $items = explode($delimiter, $list);
        $items = array_map('trim', $items);
        $items = array_filter($items);

        return array_values($items);
    }

    /**
     * Get the first alphanumeric character from a string.
     */
    public static function first($string)
    {
        $alphanumeric = preg_replace('/[^a-zA-Z0-9]/', '', $string);
        $firstLetter = substr($alphanumeric, 0, 1);

        return strtolower($firstLetter);
    }

    /**
     * Check whether a string is plain text (false if HTML).
     */
    public static function isPlainText($text)
    {
        return strip_tags($text) === $text;
    }

    /**
     * Convert plain text to HTML, only by wrapping lines in <p> tags. Returns the
     * unmodified string if HTML tags are already present.
     */
    public static function plainTextToHtml($text)
    {
        if (self::isPlainText($text)) {
            $lines = explode("\n", $text);
            $lines = array_map('trim', $lines);
            $lines = array_filter($lines);

            $html = '';

            foreach ($lines as $line) {
                $html .= '<p>' . trim($line) . '</p>' . "\n";
            }

            $html = trim($html);

            return $html;
        } else {
            return $text;
        }
    }

    /**
     * Convert HTML to plain text.
     */
    public static function htmlToPlainText($html)
    {
        // Sanitize existing HTML
        $html = self::sanitizeHtml($html);

        // Strip tags
        $text = strip_tags($html);

        // Tidy spaces
        $text = str_replace('&nbsp;', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        // Sanitize
        $text = self::sanitizeText($text);

        return $text;
    }

    /**
     * Generate a string of random characters, optionally including numbers.
     */
    public static function random($length = 10, $includeNumbers = false)
    {
        if ($length < 1) {
            throw new Exception('String length must be 1 or greater.');
        }

        $str = '';

        $characters = array_merge(range('A', 'Z'), range('a', 'z'));

        if ($includeNumbers) {
            $characters = array_merge($characters, range(0, 9));
        }

        for ($i = 0; $i < $length; $i++) {
            $k = array_rand($characters);
            $str .= $characters[$k];
        }

        return $str;
    }

    /**
     * Generate a string which appears like a word, where vowels are more common.
     */
    public static function randomWord($length = 10)
    {
        if ($length < 1) {
            throw new Exception('String length must be 1 or greater.');
        }

        $str = '';

        $vowels = array('a', 'e', 'i', 'o', 'u');
        $characters = range('a', 'z');

        // Half the time, choose only from vowels.
        for ($i = 0; $i < $length; $i++) {
            if ($i % 2 == 0) {
                $k = array_rand($vowels);
                $c = $vowels[$k];
            } else {
                $k = array_rand($characters);
                $c = $characters[$k];
            }

            $str .= $c;
        }

        return $str;
    }

    /**
     * Generate a "sentence" of random strings from 1 to 10 characters long. Average
     * word lengths for the english language is 5 characters.
     */
    public static function randomSentence($length)
    {
        $str = '';

        $max = 10;
        $wordLengths = range(1, $max);

        while (strlen($str) < ($length - $max - 2)) {
            $k = array_rand($wordLengths);
            $str .= self::randomWord($wordLengths[$k]) . ' ';
        }

        $finalWordLength = $length - strlen($str) - 1;

        $str .= self::randomWord($finalWordLength) . '.';

        return ucfirst(strtolower($str));
    }

    /**
     * Generate a paragraph of random length. Average length of an english sentence
     * is about 90 characters.
     */
    public static function randomParagraph($length)
    {
        $str = '';

        $max = 90;
        $sentenceLengths = range(5, $max);

        while (strlen($str) <= ($length - $max - 1)) {
            $k = array_rand($sentenceLengths);
            $str .= self::randomSentence($sentenceLengths[$k] - 2) . ' ';
        }

        $finalSentenceLength = $length - strlen($str);

        $str .= self::randomSentence($finalSentenceLength);

        return $str;
    }

    /**
     * Generate a random page of text with n paragraphs.
     */
    public static function randomPage($paragraphs)
    {
        $str = '';

        $paragraphLengths = range(90, 450);

        for ($i = 0; $i < $paragraphs; $i++) {
            $k = array_rand($paragraphLengths);
            $string .= '<p>' . self::randomParagraph($paragraphLengths[$k]) . "</p>\n";
        }

        return $string;
    }
}
