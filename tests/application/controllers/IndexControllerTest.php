<?php
class IndexControllerTest extends ControllerTestCase {

    /*check requests are forwarded properly*/
    public function testIndexForwarder(){
        $this->dispatch("/");
        $this->assertController("incident");
        $this->assertAction("index");
        $this->assertResponseCode(200);
    }

}