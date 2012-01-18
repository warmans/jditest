<?php
class IndexControllerTest extends ControllerTestCase {

    public function testCanGetDefaultIndexPage(){
        $this->dispatch("/");
        $this->assertController("index");
        $this->assertAction("index");
        $this->assertResponseCode(200);
    }

}