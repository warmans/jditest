<?php

/**
 * Description of Incident
 *
 * @author Stefan
 */
class Application_Model_IncidentTable extends Zend_Db_Table_Abstract {
    protected $_name = 'incident';
    protected $_rowClass = 'Application_Model_IncidentRow';
    
    
    public function getDefaultIncidentStmnt(){        
        
        $columns = array();
        $columns[] = 'id';
        $columns[] = 'explanation';
        $columns[] = 'measures_taken';
        $columns[] = 'date_created';
        $columns[] = 'date_occurred';
        $columns[] = 'date_resolved';
        $columns[] = '(UNIX_TIMESTAMP(date_resolved) - UNIX_TIMESTAMP(date_occurred)) AS elapsed_time';
        
        $incidentStmnt = $this->select()
            ->from($this, $columns)
            ->order('date_created DESC');
        
        return $incidentStmnt;
        
    }
    
}