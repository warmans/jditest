<?php
/**
 * Description of String
 *
 * @author Stefan
 */
abstract class Util_String {
    public static function shortenString($str, $length=255){
        $str = str_replace("\n", '', $str);
        if (strlen($str) <= $length) {
            return $str;
        } else {
            $str = wordwrap($str, $length);
            $str = substr($str, 0, strpos($str, "\n"));
            return $str.'[...]';
        }
    }
}