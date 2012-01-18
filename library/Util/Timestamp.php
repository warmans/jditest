<?php
/**
 * Description of String
 *
 * @author Stefan
 */
abstract class Util_Timestamp {
    
    public static function secondsToLabel($seconds){
        
        switch(TRUE):
            case ($seconds < 60):
                return $seconds.' seconds';
            case ($seconds >= 60 && $seconds < 3600):
                return ($seconds / 60).' minutes';
            case ($seconds >= 3600 && $seconds < 86400):
                return ($seconds / 60 / 60).' hours';        
            case ($seconds > 86400):
                return ($seconds / 60 / 60 / 24).' days';       
           default: 
               return 'NA';
        endswitch;
    }
    
}