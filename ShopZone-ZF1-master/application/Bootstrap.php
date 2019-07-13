<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
   
 protected function _initMyDb() {
    $dbAdapter = Zend_Db::factory('PDO_MYSQL', array(
         'host'     => '127.0.0.1',
         'username' => 'root',
         'password' => 'root',
         'dbname'   => 'ShopZoneDB'
    ));
    Zend_Db_Table::setDefaultAdapter($dbAdapter);
  }
  
  protected function _initerror() {
  $plugin = new Zend_Controller_Plugin_ErrorHandler();
  $plugin->setErrorHandlerModule('default')
       ->setErrorHandlerController('index')
       ->setErrorHandlerAction('notfound');

    $front = Zend_Controller_Front::getInstance();
    $front->registerPlugin($plugin);
  }
  
  protected function _initConfig()
    {
        Zend_Session::start();
     $autoloader = Zend_Loader_Autoloader::getInstance();
     $autoloader->registerNamespace('My_');
     
        /**
        below 2 lines are added so that, I can fetch the db values in helper file
        at /zend_tutorial/library/My/Controller/Helper\Acl.php
        :::: zend_tutorial is directory name of my local project.
        */
//        $this->bootstrap('db'); // Bootstrap the db resource from configuration
//        $db = $this->getResource('db'); // get the db object here, if necessary
        /** ******************** */
        
        
        $helper= new My_Controller_Helper_Acl();
        $helper->setRoles();
        $helper->setResources();
        $helper->setPrivilages();
        $helper->setAcl();
        
        // comment above 5 lines to remove ACL functionality

    }
    
    protected function _initPlugins() 
    {
        $objFront = Zend_Controller_Front::getInstance();
        $objFront->registerPlugin(new My_Controller_Plugin_Acl());
        return $objFront;
        
        // comment above 3 lines to remove ACL functionality
    }

}
  
  
  

