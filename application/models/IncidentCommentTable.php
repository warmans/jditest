<?php

/**
 * Description of Incident
 *
 * @author Stefan
 */
class Application_Model_IncidentCommentTable extends Zend_Db_Table_Abstract {
    protected $_name = 'incident_comment';
    protected $_rowClass = 'Application_Model_IncidentCommentRow';
}