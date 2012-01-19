<?php

class IncidentController extends Zend_Controller_Action
{

    protected $_redirector;
    protected $_flashMessenger;


    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_redirector = $this->_helper->getHelper('Redirector');
        
        //always add messages to view
        $this->view->messages = $this->_flashMessenger->getMessages();
        
        parent::init();
    }
    
    public function indexAction(){
        
        /*get table instance*/
        $incidentTable = new Application_Model_IncidentTable();
        
        /*latest three incidents*/
        $latestStmnt = $incidentTable->getDefaultIncidentStmnt();
        $latestStmnt->limit(3); //add limit
        $this->view->latestIncidents =  $incidentTable->fetchAll($latestStmnt);
        
        /*total incidents*/
        $totalIncidents = $this->_getTableCount($incidentTable);
                
        /*total comments*/
        $commentTable = new Application_Model_IncidentCommentTable();
        $totalComments = $this->_getTableCount($commentTable);
        
        $this->view->helpTitle = 'System Stats';
        $this->view->helpContent = $this->view->partial('partials/systemStats.phtml', array(
            'stats'=>array(
                'Total Incidents'=>$totalIncidents,
                'Total Comments'=>$totalComments,
            )
        ));
       
    }

    public function archiveAction() {
        
        $page = (int)($this->_request->getParam('page')) ?: 0;
        $numPerPage = 5;
                
        $this->view->page = $page;
        $this->view->numPerPage = $numPerPage;
        
        /*get table instance*/
        $incidentTable = new Application_Model_IncidentTable();
        
        /*get total count for paginated list*/
        $this->view->totalRecords = $totalRecords = $this->_getTableCount($incidentTable);
        
        /*get the records*/
        if($totalRecords > 0){
            $stmnt = $incidentTable->getDefaultIncidentStmnt();
            $stmnt->limit($numPerPage, (max(0, $page))*$numPerPage);
            $this->view->records =  $incidentTable->fetchAll($stmnt);
        } else {
            $this->view->records = array();
        }
        
    }
            
    public function createAction(){
        
        /*get a new record from the DB table*/
        $incidentTable = new Application_Model_IncidentTable();
        $newIncident = $incidentTable->createRow();
                
        /*get the form - requires instance of validated row class to implement form validation*/
        $incidentForm = $this->_getIncidentForm($newIncident);
        $incidentForm->setAction('/incident/create');
        
        /*handle any post data and potentially redirect*/
        $this->_handleIncidentForm($incidentForm, $newIncident);
        
        $this->view->form = $incidentForm;
        $this->view->helpContent = 'Use this form to report downtime that affects more than one client that is not scheduled and was unexpected. Report must be submitted within 24 hours of incident.';
    }
    
    public function readAction(){
        
        /*get incident*/
        $incidentRecord = $this->_getRecordOrFail();
        $this->view->record = $incidentRecord;
        
        /*get existing comments*/
        $commentTable = new Application_Model_IncidentCommentTable();
        $incidentCommentsStmnt = $commentTable->select()
            ->from($commentTable)
            ->where('incident_id = ?', $incidentRecord->id)
            ->order('date_created DESC');
        $this->view->comments = $commentTable->fetchAll($incidentCommentsStmnt);
        
        /*new comment - required for form validaiton*/
        $newComment = $commentTable->fetchNew();
        
        $form = $this->_getCommentForm($newComment);
        $form->setAction('/incident/read/'.$incidentRecord->id);
        $form->addElement('hidden', 'incident_id', array('value'=>$incidentRecord->id));
        $form->setAttrib("class", 'comment-form');
        
        /*handle submissions*/
        if($this->getRequest()->isPost() && $form->isValid($_POST)){
                    
           //set the data from the form
            $newComment->setFromArray($form->getValues());

            //try and save the record and handle the result
            if((bool)$newComment->save()){

                //note succes and exit to read page
                $this->_flashMessenger->addMessage(array('msg'=>'Comment Has Been Added', 'class'=>'success'));
                $this->_redirector->gotoUrlAndExit('/incident/read/'.$incidentRecord->id);

            } else {
                $this->_flashMessenger->addMessage(
                    array(
                        'msg'=>'Sorry and error occured: '.$newComment->getValidationErrorsAsString(), 
                        'class'=>'error'
                    )
                );
            }
        }
                        
        $this->view->commentForm = $form;
    }
    
    private function _getCommentForm(ModelValidation_RowAbstract $record){
        
        $fieldList = array();
        $fieldList[] = array(
            'type'=>'textarea', 
            'name'=>'content', 
            'label'=>'Comment', 
            'required'=>TRUE, 
            'attr' => array('class' => 'span7'));
        
        $fieldList[] = array(
            'type'=>'text', 
            'label'=>'Author', 
            'name'=>'author', 
            'required'=>TRUE, 
            'attr' => array('class' => 'span7'));
                
        ///build the form
        $form = $this->_arrayToForm($fieldList, $record);
                        
        //add the submit button
        $form->addElement('submit', 'submit', array('label' => 'Submit Comment', 'class'=>'btn primary'));
        
        return $form;
    }
    
    public function updateAction(){
        
        $record = $this->_getRecordOrFail();
        
        $incidentForm = $this->_getIncidentForm($record);
        $incidentForm->setAction('/incident/update?id='.$record->id);
        
        $this->_handleIncidentForm($incidentForm, $record);
        
        $this->view->form = $incidentForm;
    }
    
    private function _getRecordOrFail(){
        $id = $this->_request->getParam('id');
        
        if(!$id){
            //note succes and exit to read page
            $this->_flashMessenger->addMessage(array('msg'=>'Edit Failed: No Incident Specified', 'class'=>'error'));
            $this->_redirector->gotoUrlAndExit('/incident/index');
        }
        
        $incidentTable = new Application_Model_IncidentTable();
        
        $stmnt = $incidentTable->getDefaultIncidentStmnt();
        $stmnt->where('id = ?', $id);
        $stmnt->limit(1);
                                
        $incidentsFound = $incidentTable->fetchAll($stmnt);
        if(count($incidentsFound) < 1){
            //note succes and exit to read page
            $this->_flashMessenger->addMessage(array('msg'=>'Incident could not be found', 'class'=>'error'));
            $this->_redirector->gotoUrlAndExit('/incident/index');
        }
        
        return $incidentsFound[0];
    }
    
    public function destroyAction(){
        //not implemented
    }
    
    private function _handleIncidentForm(Zend_Form $incidentForm, ModelValidation_RowAbstract $record){
        
        if(!$this->getRequest()->isPost()){
            return; //form hasn't been submitted
        }
                        
        //check form validates
        if(!$incidentForm->isValid($_POST)){
            return; //something isn't right - return and re-render the form
        }

        //set the data from the form
        $record->setFromArray($incidentForm->getValues());

        //try and save the record and handle the result
        if((bool)$record->save()){

            //note succes and exit to read page
            $this->_flashMessenger->addMessage(array('msg'=>'Incident Saved', 'class'=>'success'));
            $this->_redirector->gotoUrlAndExit('/incident/read/'.$record->id);

        } else {
            $this->_flashMessenger->addMessage(
                array(
                    'msg'=>'Sorry and error occured: '.$record->getValidationErrorsAsString(), 
                    'class'=>'error'
                )
            );
        }
    }
    
    private function _getIncidentForm(ModelValidation_RowAbstract $record){
        
        $fieldList = array();
        $fieldList[] = array(
            'type'=>'text', 
            'name'=>'date_occurred',
            'label'=>'Date / Time of Incident', 
            'required'=>TRUE, 
            'attr'=>array('class'=>'datepicker span5'));
        
        $fieldList[] = array(
            'type'=>'text', 
            'name'=>'date_resolved',
            'label'=>'Date / Time of Resolution', 
            'required'=>TRUE,
            'attr'=>array('class'=>'datepicker span5'));
        
        $fieldList[] = array(
            'type'=>'textarea', 
            'name'=>'explanation', 
            'label'=>'General Explanation', 
            'required'=>TRUE, 
            'attr' => array('class' => 'span7'));
        
        $fieldList[] = array(
            'type'=>'textarea', 
            'label'=>'Preventive Measures Taken', 
            'name'=>'measures_taken', 
            'required'=>TRUE, 
            'attr' => array('class' => 'span7'));
        
        ///build the form
        $form = $this->_arrayToForm($fieldList, $record);
                        
        //add the submit button
        $form->addElement('submit', 'submit', array('label' => 'Save Incident', 'class'=>'btn primary'));
        
        return $form;
    }
    
    private function _arrayToForm($fieldList, ModelValidation_RowAbstract $record){
        
        $form = new Zend_Form();
        $form->setMethod('post');
        
        //create each field and add it to the form 
        foreach($fieldList as $fieldKey=>$fieldInfo):
            
            //create the field object 
            $curField = $form->createElement($fieldInfo['type'], $fieldInfo['name']);
        
            //set the label
            $curField->setLabel($fieldInfo['label']);
        
            //add validators from record model
            foreach($record->getFieldValidators($fieldInfo['name']) as $key=>$validator):
                $curField->addValidator($validator);
            endforeach;
            $curField->setRequired($fieldInfo['required']);
            
            //add existing data (i.e. update)
            $curField->setValue($record->$fieldInfo['name']);
            
            //set css classes etc.
            if(!empty($fieldInfo['attr'])){
                foreach($fieldInfo['attr'] as $attrName=>$attrValue):
                    $curField->setAttrib($attrName, $attrValue);
                endforeach;
            }
            
            $curField->setDecorators(
                array(
                    new TwitterBootstrap_Form_Decorator_Composite(),
                    array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div'))
                )
            );
                                                
            //add to form
            $form->addElement($curField);
                        
        endforeach;
        
        return $form;
    }
    
    private function _getTableCount($table){

        $countStmnt = $table->select()
            ->from($table, array('COUNT(*) AS total_records'));
        return $table->fetchAll($countStmnt)->current()->total_records;
        
    }
    
}