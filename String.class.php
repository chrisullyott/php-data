<?php

/**
 * Methods for string generation and manipulation.
 */
class String
{
    /**
     * Generate a string of random characters, optionally including numbers.
     */
    public static function randomString($length = 10, $includeNumbers = false)
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
