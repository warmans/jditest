<?php
/**
 * Description of String
 *
 * @author Stefan
 */
abstract class Util_Timestamp {
    
    public static function secondsToLabel($seconds){        
        
        switch(TRUE):
            case ($seconds <= 60):
                $value = $seconds;
                $unit = 'seconds';
                break;
            case ($seconds > 60 && $seconds <= 3600):
                $value =($seconds / 60);
                $unit = 'minutes';
                break;
            case ($seconds > 3600 && $seconds <= 86400):
                $value = ($seconds / 60 / 60);
                $unit = 'hours';
                break;                
            case ($seconds > 86400):
                $value = ($seconds / 60 / 60 / 24);
                $unit = 'days';
                break;       
        endswitch;
        
        return round($value, 2).' '.$unit;
    }
    
}