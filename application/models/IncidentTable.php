<?php

/**
 * Description of Incident
 *
 * @author Stefan
 */
class Application_Model_IncidentTable extends Zend_Db_Table_Abstract {
    protected $_name = 'incident';
    protected $_rowClass = 'Application_Model_IncidentRow';
    
    
    /**
     * Just get a default select object for this table.
     * 
     * @return Zend_Db_Select object
     */
    public function getDefaultIncidentStmnt(){        
        
        $incidentStmnt = $this->select()
            ->from($this)
            ->order('date_created DESC');
        
        return $incidentStmnt;
    }
    
}