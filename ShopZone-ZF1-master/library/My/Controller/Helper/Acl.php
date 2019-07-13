<?php
     class My_Controller_Helper_Acl {
     
        public $acl;
        public function __construct()
        {
            // first create Zend_Acl instance
            $this->acl = new Zend_Acl();
        }
        public function setRoles()
        {
             $this->acl->addRole(new Zend_Acl_Role('guest'));
             $this->acl->addRole(new Zend_Acl_Role('user'));
             $this->acl->addRole(new Zend_Acl_Role('shop'));
             $this->acl->addRole(new Zend_Acl_Role('admin'));
            // added roles above statically

            // Now adding roles below dynamically i.e. from db
            // class Application_Model_DbTable_EntitiesRoleTable is there at -
            // /application/models/DbTable/EntitiesRoleTable.php
            // check comments in Bootstarp.php mentioned below
            // need to add two lines of code in Bootstarp.php
            // with which this helper file can fetch the data from database.
            
            
            // I have model file for roles table with 2 columns roles.id and roles.name
            /*
            roles.id is:: int - primary key - auto increment
            roles.name is:: varchar 50

            Then have some entries in roles table like
             id    name
             -------------------
             1      guest
             2      editor
             3      admin

             */

            // at /zend_tutorial/application/models/DbTable/EntitiesRoleTable.php
            
//            $model = new Application_Model_DbTable_EntitiesRoleTable();
//            $data = $model->getAll();
//            
//            $guest_role_in_db = 0;
//            foreach($data as $roles)
//            {
//                
//                $this->acl->addRole(new Zend_Acl_Role($roles['name']));
//                if($roles['name'] == 'guest')
//                {
//                    $guest_role_in_db = 1;
//                }
//            }
//            
//            if(!$guest_role_in_db)
//            {
//                $this->acl->addRole(new Zend_Acl_Role('guest'));
//            }
        }

        public function setResources()
        {
            // Adding resources action-wise
            // that means, these actions of "every controller" are going to get validated 
            // for access for guest or editor or admin users (roles) etc.
            
            $this->acl->add(new Zend_Acl_Resource('index'));
            $this->acl->add(new Zend_Acl_Resource('user'));
            $this->acl->add(new Zend_Acl_Resource('shop'));
            $this->acl->add(new Zend_Acl_Resource('admin'));
            
            
            // setting up access control as per controller
            // i.e. access control will work for these controllers only.
            // Suppose I want to control the access of only 2 controllers,
            // for ex. for NewsController and JobBoardController
            // you can also fetch these values from DB as done for roles
            // as above in function setRoles().
//            
//            $this->acl->add(new Zend_Acl_Resource('news'));
//            $this->acl->add(new Zend_Acl_Resource('job-board'));

        }

        public function setPrivilages()
        {
        
            // Setting privilages for actions of all controllers            
            // $this->acl->allow('guest',null, array('view', 'index'));
            // $this->acl->allow('editor',array('view','edit'));
            // $this->acl->allow('admin');
            
            // Setting privilages for actions as per particular controller
            // $this->acl->allow('<role>','<controller>', <array of controller actions>);
            // You can also fetch it from DB.
            
//            // $this->acl->deny('guest','news', 'index');
//            $this->acl->allow('guest','news', array( 'demo1', 'view', 'index'));
//            $this->acl->allow('guest','job-board', array('index'));
//
//            $this->acl->allow('editor','news', array( 'edit', 'view', 'index')); 
//            $this->acl->allow('editor','job-board', array('edit', 'index'));
//
//            $this->acl->allow('admin');
            //$this->acl->allow('guest');
        //Guest ACL
            $this->acl->deny('guest',array('user','shop','admin'));
            $this->acl->allow('guest','index');
            
        //User ACL
            $this->acl->deny('user',array('shop','admin'));
            $this->acl->allow('user','user');
            $this->acl->allow('user','index',array('logout','notfound'));
            
        
        // Shop ACL
            $this->acl->deny('shop',array('user','admin'));
            $this->acl->allow('shop',array('index','shop'));
            
        //Admin ACL
            //$this->acl->deny('admin',array('user','shop'));
            //$this->acl->allow('admin',array('index','admin'));
            $this->acl->allow('admin');
            // Note that the actions which are not mentioned above i.e. inside array of
            // controller-action - becomes access-denied automatically
            // as in above example, news controller also have one more action demo2,
            // but demo2 is not mentioned in above allow actions, so 
            // when guest tries to access the action - demo2, it would not show the 
            // content of demo2, rather It would show content of error/index.phtml
        }
        
        public function setAcl()
        {
            // store acl object using Zend_Registry for future use. This is compulsory.
            Zend_Registry::set('acl',$this->acl);
        }
     }