<?php
/*use controller testcase so app is bootstrapped correctly*/
class ModelValidation_CallbacksTest extends ControllerTestCase {
    
    public function setUp(){        
        parent::setUp();
    }
    
    public function testDateGreaterThanEqual(){
        $context = array('date_resolved'=>'2000-01-01 00:00:00');
        
        $callback = ModelValidation_Callbacks::dateGreaterThanField('date_resolved');
        $result = $callback('2000-01-01 00:00:00', $context);
        
        $this->assertTrue($result);
    }
    
    public function testDateGreaterThanTrue(){
        $context = array('date_resolved'=>'2000-01-01 00:00:00');
        
        $callback = ModelValidation_Callbacks::dateGreaterThanField('date_resolved');
        $result = $callback('2000-01-02 00:00:00', $context);
        
        $this->assertTrue($result);
    }
    
    public function testDateGreaterThanFalse(){
        $context = array('date_resolved'=>'2000-01-02 00:00:00');
        
        $callback = ModelValidation_Callbacks::dateGreaterThanField('date_resolved');
        $result = $callback('2000-01-01 00:00:00', $context);
        
        $this->assertFalse($result);
    }
    
}