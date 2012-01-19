<?php
/*use controller testcase so app is bootstrapped correctly*/
class Util_StringTest extends ControllerTestCase {
    
    public function setUp(){        
        parent::setUp();
    }
    
    public function testShortenStringLonger(){
        
        $expectedString = 'this should finish.';
        $longerString = $expectedString.' asdfg asdfg qwewqew';
        
        $result = Util_String::shortenString($longerString, 25);
        
        $this->assertEquals($expectedString.'[...]', $result);
    }
    
    public function testShortenStringShorter(){
        
        $string = str_repeat('a', 100);
        $result = Util_String::shortenString($string, 256);
        
        $this->assertNotContains($result, '[...]');
    }
    
    public function testShortenStringExact(){
        
        $string = str_repeat('a', 100);
        $result = Util_String::shortenString($string, 100);
        
        $this->assertNotContains($result, '[...]');
    }
    
}