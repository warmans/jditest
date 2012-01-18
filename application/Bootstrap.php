<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype(){
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    
    protected function _initNamespaces() {
        Zend_Loader_Autoloader::getInstance()->registerNamespace('ModelValidation_');
        Zend_Loader_Autoloader::getInstance()->registerNamespace('TwitterBootstrap_');
        Zend_Loader_Autoloader::getInstance()->registerNamespace('Util_');
    }
    
    protected function _initRoutes(){
        $router = new Zend_Controller_Router_Rewrite();
        $router->addRoute('incident_read', new Zend_Controller_Router_Route('incident/read/:id', array('controller' => 'incident', 'action' => 'read')));
        $router->addRoute('incident_update', new Zend_Controller_Router_Route('incident/update/:id', array('controller' => 'incident', 'action' => 'update')));
        $router->addRoute('incident_index_paging', new Zend_Controller_Router_Route('incident/index/page/:page', array('controller' => 'incident', 'action' => 'index')));
       
        $this->bootstrap('frontController');
        $this->frontController->setRouter($router);
    }
    
    protected function _initFirebug(){
        $logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Firebug();
        $logger->addWriter($writer);
        Zend_Registry::set('firebug', $logger);
    }
}