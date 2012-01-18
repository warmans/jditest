<?php

/**
 * Description of IncidentRow
 *
 * @author Stefan
 */
class Application_Model_IncidentCommentRow extends ModelValidation_RowAbstract {
    
    protected function _initValidators() {
                
        /*must  be set*/
        $this->addValidator('author', new Zend_Validate_NotEmpty());
        $this->addValidator('content', new Zend_Validate_NotEmpty());
        
        parent::_initValidators();
    }
    
}