<?php
/*use controller testcase so app is bootstrapped correctly*/
class Util_TimestampTest extends ControllerTestCase {
    
    public function setUp(){        
        parent::setUp();
    }
    
    public function testNegativeSeconds(){
        $result = Util_Timestamp::secondsToLabel(-10);
        $this->assertEquals('-10 seconds', $result);
    }
    
    public function testNoSeconds(){
        $result = Util_Timestamp::secondsToLabel(0);
        $this->assertEquals('0 seconds', $result);
    }
    
    public function testSeconds(){
        $result = Util_Timestamp::secondsToLabel(10);
        $this->assertEquals('10 seconds', $result);
    }
    
    public function testMinutes(){
        $result = Util_Timestamp::secondsToLabel(120);
        $this->assertEquals('2 minutes', $result);
    }
    
    public function testHours(){
        $result = Util_Timestamp::secondsToLabel(3600);
        $this->assertEquals('60 minutes', $result);
    }
    
    public function testTestSecondsInDay(){
        $result = Util_Timestamp::secondsToLabel(86400);
        $this->assertEquals('24 hours', $result);
    }    
    
    public function testDays(){
        $result = Util_Timestamp::secondsToLabel(172800);
        $this->assertEquals('2 days', $result);
    }    
}