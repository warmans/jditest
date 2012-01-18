<?php

/**
 * Description of Incident
 *
 * @author Stefan
 */
class Application_Model_IncidentTable extends Zend_Db_Table_Abstract {
    protected $_name = 'incident';
    protected $_rowClass = 'Application_Model_IncidentRow';
}