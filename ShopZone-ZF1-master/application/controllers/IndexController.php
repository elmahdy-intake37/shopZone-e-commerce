<?php

class IndexController extends Zend_Controller_Action
{

    public $fpS = null;
    public $gp=null;
    public function init()
    {
        
       
         $this->fpS = new Zend_Session_Namespace('facebook');
         $authorization = Zend_Auth::getInstance();
         $fbsession = new Zend_Session_Namespace('facebook');
          $this->gp = new Zend_Session_Namespace('google');
         $gpsession = new Zend_Session_Namespace('google');

         $request=$this->getRequest();
         $actionName=$request->getActionName();

            //for arabic
      $request= $this->getRequest()->getParam('ln');


         if(empty($request)){
           $this->language = new Zend_Session_Namespace('language');
           $this->language->type= isset($this->language->type)?$this->language->type:"En";

      }
      else{
          $this->language= new Zend_Session_Namespace('language');
          $this->language->type = $request ;



      }
            $this->view->language=$this->language;

    }

    public function indexAction()
    {
         $category_model= new Application_Model_Category();
        $this->view->category = $category_model->listAll();
         $select_model=new Application_Model_Product();
        $this->view->rate_product =$select_model->selectproductrate();
                $Slider_model=new Application_Model_Slider();
        
        $this->view->select_image =$Slider_model->slider();
    }

    public function listCategoryAction()
    {
        $category_model= new Application_Model_Category();
        $this->view->category = $category_model->listAll();
    }

    public function detailsProductAction()
    {

        $product_model=new Application_Model_Product();
        $product_id = $this->_request->getParam("uid");
        $product_data = $product_model->ProductDetails($product_id);
        
        $this->view->product_data=$product_data[0];
        $addcart=new Application_Form_Addtocart();
        $this->view->form=$addcart;

 
    }

    public function listProductAction()
    {
         $product_model=new Application_Model_Product();
        $id = $this->_request->getParam('uid');
        $this->view->product =$product_model->listProdCat($id);

    }

    public function selectrateprodAction()
    {
         $select_model=new Application_Model_Product();
        
        $this->view->rate_product =$select_model->selectproductrate();

    }

    public function sliderAction()
    {
        $Slider_model=new Application_Model_Slider();
        
        $this->view->select_image =$Slider_model->slider();
    }

    public function registerAction()
    {
        $form=new Application_Form_Signup();
        $this->view->user_form= $form;
        $request=$this->getRequest();
        if($request->isPost()){
            if($form->isValid($request->getPost())){
                $user_model = new Application_Model_Customer();
                $user_model-> SignUp($request->getParams());
            }
        }
    }

    public function loginAction()
    {

        $loginForm=new Application_Form_Login();
        $request=$this->getRequest();
        if($request->isPost()){
          if ($loginForm->isValid($request->getPost())) {
            $email=$request->getParam('email');
            
            $pass=md5($request->getParam('pass'));


            $dp=Zend_Db_Table::getDefaultAdapter();
            $adapter=new Zend_Auth_Adapter_DbTable($dp,'customer','email','password');
            $adapter->setIdentity($email);
            $adapter->setCredential($pass);
            $result=$adapter->authenticate();
            if ($result->isValid()) {
              $sessionDataObj=$adapter->getResultRowObject(['id','email','name','adress','type','is_active']);
              
            if($sessionDataObj->is_active == 'true'){
                $auth=Zend_Auth::getInstance();
                $storage=$auth->getStorage();
                $storage->write($sessionDataObj);
                $usersNs = new Zend_Session_NameSpace("members");
                $usersNs->userType = $sessionDataObj->type;
                $path = "/".$sessionDataObj->type;
                $this->redirect($path);


            }else{
                 echo "You are blocked";


            }
          }
          else{
            $this->view->error_message="Invalid Email or password";

          }
        }
    }
              $this->view->loginform_var=$loginForm;
               $fb = new Facebook\Facebook([
        'app_id' => '1767915360190950', // Replace {app-id} with your app id
        'app_secret' => '150fbee6425745ae2d8de9092073afef',
        'default_graph_version' => 'v2.2',
]);

        $helper = $fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl($this->view->serverUrl() .'/index/facebookcallback',array('scope' => 'email'));
        $this->view->facebookUrl =$loginUrl;
       
    }

    public function logoutAction()
    {
        $auth=Zend_Auth::getInstance();
        $auth->clearIdentity();
        Zend_Session::namespaceUnset('facebook');
        Zend_Session::namespaceUnset('google');

        Zend_Session::namespaceUnset('members');
        return $this->redirect('index/login');
    }

    public function facebookcallbackAction()
    {
      $fb = new Facebook\Facebook([
        'app_id' => '1767915360190950', // Replace {app-id} with your app id
        'app_secret' => '150fbee6425745ae2d8de9092073afef',
        'default_graph_version' => 'v2.2',
        ]);

$helper = $fb->getRedirectLoginHelper();

try {
$accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
// When Graph returns an error
echo 'Graph returned an error: ' . $e->getMessage();
exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
// When validation fails or other local issues
echo 'Facebook SDK returned an error: ' . $e->getMessage();
exit;
}

if (! isset($accessToken)) {
if ($helper->getError()) {
header('HTTP/1.0 401 Unauthorized');
echo "Error: " . $helper->getError() . "\n";
echo "Error Code: " . $helper->getErrorCode() . "\n";
echo "Error Reason: " . $helper->getErrorReason() . "\n";
echo "Error Description: " . $helper->getErrorDescription() . "\n";
} else {
header('HTTP/1.0 400 Bad Request');
echo 'Bad request';
}
exit;
}
// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

if (! $accessToken->isLongLived()) {
// Exchanges a short-lived access token for a long-lived one
try {
$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
} catch (Facebook\Exceptions\FacebookSDKException $e) {
echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
exit;
}

echo '<h3>Long-lived</h3>';

}
$fb->setDefaultAccessToken($accessToken);

try {
$response = $fb->get('/me?fields=id,name,email');
$userNode = $response->getGraphUser();

}
catch (Facebook\Exceptions\FacebookResponseException $e) {
// When Graph returns an error
echo 'Graph returned an error: ' . $e->getMessage();
Exit;
}
catch (Facebook\Exceptions\FacebookSDKException $e) {
// When validation fails or other local issues
echo 'Facebook SDK returned an error: ' . $e->getMessage();
Exit;
}

$this->fpS->name = $userNode['name'];
$this->fpS->email=$userNode['email'];
$type='user';
$this->fpS->type=$type;

            $user_model = new Application_Model_Customer();
            $password = $user_model->randomCode();

$user=array("name"=>$userNode['name'],"email"=>$userNode['email'],"type"=>$type,"password"=>$password,"address"=>"");
    $result= $user_model->fetchRow($user_model->select()->where('email like ?', $userNode['email']));

             if(!$result){
                   $subject = "Your Password";
                    $body = "your password is ".$password;
                    $user_model->sendEmail($userNode['email'], $subject, $body);
                $user_model-> SignUp($user);
            }
                $usersNs = new Zend_Session_NameSpace("members");
               $usersNs->userType = "user";

                $path = "/".$usersNs->userType;
                $this->redirect($path);

    }

    public function searchAction()
    {
        $this->_helper->layout('layout')->disableLayout();

        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
               $name=$_POST['searchword'];
              
                
                $indexSearch = new Application_Model_Product();
                $result = $indexSearch ->searchByName($name);
                $this->view->indexSearch = $result;

        }
    }
    }

    public function notfoundAction()
    {

        }

    public function ressetpasswordAction()
    {
        $reset_form = new Application_Form_Resetpasswordform();
        $this->view->reset_form =$reset_form;
        $request=$this->getRequest();
        if($request->isPost()){
            if($reset_form->isValid($request->getPost())){
                $user_model = new Application_Model_Customer();
                $e = $request->getParam('email');
                $result = $user_model->getEmail($e);
                print_r($result);
                if (isset($result[0]['email'])){
                    $code = $user_model->randomCode();
                    $ee =$result[0]['email'];
                    
                                        $data = array(
                       'reset_password' => $code,
                       
                    );
                    $where = $user_model->getAdapter()->quoteInto('email = ?', $ee);
                    
                    
                    $user_model->update($data,$where);
                    $subject = "Reset Password";
                    $body = "http://shopzone.com/index/changepassword/code/".$code;
                    $user_model->sendEmail($ee, $subject, $body);
                    $this->redirect("/index");
               
                
                }
                }
        }
    }

    public function changepasswordAction()
    {
        $change_form = new Application_Form_Changepasswordform();
        $request=$this->getRequest();
        if ($request->getUserParam("code")){
            $code = $request->getUserParam("code");
                    $user_model = new Application_Model_Customer();
                   $result = $user_model->checkCode($code);
                   if (isset($result[0]['reset_password'])){
                   $this->view->change_form =$change_form;
                   $request=$this->getRequest();
                   if($request->isPost()){
            if($change_form->isValid($request->getPost())){
                $password = md5($request->getParam('password'));
                $email = $user_model->getCodeEmail($code);
                $email = $email[0]['email'];
                
                $data = array(
                       'password' => $password,
                       
                    );
                    $where = $user_model->getAdapter()->quoteInto('email = ?', $email);
                    $user_model->update($data,$where);
                    $user_model->deleteCode($email);
                    $this->redirect("/index");
                
            }
            }
                 
                   }
                   else
                   {
                      $this->view->change_form = "invalid code";
                   }
        }
        
    }

    public function googleloginAction()
    {
     $client_id = '241152925905-57dfnjjj5cnbu2dmoqftboqq4s8ofghe.apps.googleusercontent.com';
     $client_secret = 'icPt_AcGD_KvonAOC_ReBj-F';
    $client = new Google_Client();

    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->addScope('email');
    $service = new Google_Service_Oauth2($client);

    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    } else {
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/index/gpcallback';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

    }

    public function gpcallbackAction()
    {
    $client_id = '681093495489-er5i9j0lu0ood3jb2u6qairgcselmaf4.apps.googleusercontent.com';
    $client_secret = 'TirqTRefhxzvyNj_ad_s6BO5';
    $client = new Google_Client();

    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/index/gpcallback');
    $client->addScope('email');
    $service = new Google_Service_Oauth2($client);

    if (! isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    } else {
    $client->authenticate($_GET['code']);
    $userNode = $service->userinfo->get();//get user info
        $this->gp->name = $userNode['name'];
        $type='user';
        $this->gp->email=$userNode['email'];
        $type='user';
        $this->gp->type=$type;
        $user_model = new Application_Model_Customer();

       $password = $user_model->randomCode();

$user=array("name"=>$userNode['name'],"email"=>$userNode['email'],"type"=>$type,"password"=>$password,"address"=>"");

            }


    $row = $user_model->fetchRow($user_model->select()->where('email like ?', $userNode['email']));
    if(!$row){
      $subject = "Your Password";
      $body = "your password is ".$password;
      $email=$userNode['email'];
      $user_model->sendEmail($email, $subject, $body);
      
    $user_model-> SignUp($user);
    }
    $usersNs = new Zend_Session_NameSpace("members");
               $usersNs->userType = "user";

                $path = "/".$usersNs->userType;
                $this->redirect($path);
   
    }

}




    













