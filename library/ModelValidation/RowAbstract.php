<?php

/**
 * Description of Row
 *
 * @author Stefan
 */
abstract class ModelValidation_RowAbstract extends Zend_Db_Table_Row_Abstract{
    
    protected $_validationErrors = array();
    protected $_fieldValidators = array();
    
    protected function _initValidators(){        
        return;
    }
    
    /* Allows validators to be passed to ZendForm where required.*/
    public function getFieldValidators($fieldName){
        
        //populate validators
        $this->_initValidators();
        
        return !empty($this->_fieldValidators[$fieldName]) 
               ? $this->_fieldValidators[$fieldName] 
               : array();
    }
        
    public function addValidator($field, Zend_Validate_Abstract $validator){
                
        if(empty($this->_fieldValidators[$field])){
            $this->_fieldValidators[$field] = array($validator);
        } else {
            array_push($this->_fieldValidators[$field], $validator);
        }
    }
    
    private function _addValidationError($message){
        if(is_array($message)){
            $this->_validationErrors = array_merge($this->_validationErrors, $message);
        } else {
            $this->_validationErrors[] = $message;
        }
    }
    
    public function getValidationErrors(){
        return $this->_validationErrors;
    }
    
    public function getValidationErrorsAsString(){
        return implode(", ", $this->_validationErrors);
    }
        
    private function _isValid(){
        
        //populate validators
        $this->_initValidators();
        
        foreach($this->_fieldValidators as $field => $validators):
            foreach($validators as $key=>$validator):
                if(!$validator->isValid($this->$field)){
                    $this->_addValidationError($field.' failed because:'.implode(', ', $validator->getMessages()));
                }   
            endforeach;
        endforeach;
        
        return (count($this->_validationErrors) == 0) ? TRUE : FALSE;
    }
    
    public function save(){
        $valid = $this->_isValid();
        return ($valid) ? parent::save() : $valid;
    }
    
}