<?php
class IncidentControllerTest extends ControllerTestCase {

    /*check requests are forwarded properly*/
    public function testIndexPage(){
        $this->dispatch("/incident");
        $this->assertController("incident");
        $this->assertAction("index");
        $this->assertResponseCode(200);
    }
    
    public function testArchive(){
        $this->dispatch("/incident/archive");
        $this->assertController("incident");
        $this->assertAction("archive");
        $this->assertResponseCode(200);
    }
    
    public function testCreate(){
        $this->dispatch("/incident/create");
        $this->assertController("incident");
        $this->assertAction("create");
        $this->assertResponseCode(200);
    }

    /*this will fail if the DB is empty - not good*/
    public function testRead(){
        $this->dispatch("/incident/read/1");
        $this->assertController("incident");
        $this->assertAction("read");
        $this->assertResponseCode(200);
    }
    
    /*this will fail if the DB is empty - not good*/
    public function testUpdate(){
        $this->dispatch("/incident/update/1");
        $this->assertController("incident");
        $this->assertAction("update");
        $this->assertResponseCode(200);
    }    
    
}