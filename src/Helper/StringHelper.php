<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 *
 * @version Crawler v1.0 26/02/18 12:05 AM
 */

namespace App\Helper;

abstract class StringHelper
{
    /**
     * @param string $string String for counting words
     *
     * @return integer
     */
    public static function countWords($string)
    {
        return count(explode(' ', $string));
    }
}
