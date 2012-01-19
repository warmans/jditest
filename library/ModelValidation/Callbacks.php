<?php
/**
 * Description of Callbacks
 *
 * @author Stefan
 */
abstract class ModelValidation_Callbacks {
    
    public static function dateGreaterThanField($fieldName){
        $callback = 
            function($value, $context = null) use ($fieldName) {
                if(strtotime($value) >= strtotime($context[$fieldName])){
                    return TRUE;
                }                
                return FALSE;
            };
        return $callback;
    }
}