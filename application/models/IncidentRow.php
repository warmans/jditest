<?php

/**
 * Description of IncidentRow
 *
 * @author Stefan
 */
class Application_Model_IncidentRow extends ModelValidation_RowAbstract {
    
    protected function _initValidators() {
        
        /*must be an sql date*/
        $this->addValidator('date_occurred', new Zend_Validate_Date(array('format' => 'Y-m-d H:i:s')));
        $this->addValidator('date_resolved', new Zend_Validate_Date(array('format' => 'Y-m-d H:i:s')));
        
        /*an issue must be resolved after it occurred - use closure*/
        $this->addValidator('date_resolved', new Zend_Validate_Callback(
            function($value, $context = null){
                if(strtotime($value) >= strtotime($context['date_occurred'])){
                    return TRUE;
                }                
                return FALSE;
            }
        ));
        
        /*must  be set*/
        $this->addValidator('explanation', new Zend_Validate_NotEmpty());
        $this->addValidator('measures_taken', new Zend_Validate_NotEmpty());
        
        parent::_initValidators();
    }
    
}