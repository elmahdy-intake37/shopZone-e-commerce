<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        $layout = $this->_helper->layout();
        $layout->setLayout('adminlayout');

    }

    public function indexAction()
    {
        $user_model = new Application_Model_Customer();
        $this->view->countUsers = count($user_model->listusers());
    }

    public function listallusersAction()
    {
        $user_model = new Application_Model_Customer();
        $user_type = $this->_request->getParam('type');
        
        $this->view->users = $user_model->listusers($user_type);
       
       
    }

    public function userdetailsAction()
    {
         $customer_model = new Application_Model_Customer();
        $us_id = $this->_request->getParam("uid");
        $user = $customer_model->userDetails($us_id);
        $this->view->user_data = $user;
    }

    public function edituserAction()
    {
        $form = new Application_Form_Signup();
        $id=$this->_request->getParam('uid');
        $userModel= new Application_Model_Customer();
        $userData=$userModel->userDetails($id);
        $form->populate($userData);   
        $this->view->user_form=$form;
        $request = $this->getRequest();
        if($request-> isPost()){
        if($form-> isValid($request-> getPost())){
        $userModel-> updateUser ($id,$request->getPost());
        $this->redirect('/admin/listallusers ');

    }


}
    }

    public function deleteuserAction()
    {
        $user_model = new Application_Model_Customer();
        $us_id = $this->_request->getParam("uid");
        $user_model->deleteUser($us_id);
        $this->redirect("/admin/listallusers");
    }

    public function blockuserAction()
    {
          $user_model = new Application_Model_Customer();
        $us_id = $this->_request->getParam("uid");
        $user_model->blockUser($us_id);
        $this->redirect("/admin/listallusers");
    }

    public function activeuserAction()
    {
        $user_model = new Application_Model_Customer();
        $us_id = $this->_request->getParam("uid");
        $user_model->activeUser($us_id);
        $this->redirect("/admin/listallusers");
    }

    public function sendcouponAction()
    {
          $coupon=new Application_Model_Coupon();

           $request=$this->getRequest();
        if($request->isPost()){
            
        $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $code =''; 
     
     
        for($i=0;$i<30; $i++)
        {
            $code .= $chars[rand(0,strlen($chars)-1)];
        }
        $request->setParam('code', $code);
   

       $coupon-> addCoupon($request->getParams());

        }

       $uid= $request->getParam('customer');
       $discount=$request->getParam('discount');
       $customer_model = new Application_Model_Customer();
        $user = $customer_model->userDetails($uid);
        $name=$user['name'];
       
       $email=$user['email'];

        $body="Hello $name We have made a discount for you with amount of $discount %
                for the upcoming purchase Order.
    write this in discount field when purchasing next time :-
    $code";
    $subject='Coupon';
   $customer_model=new Application_Model_Customer();
    $send_email=$customer_model->sendEmail($email,$subject,$body);
   $this->redirect('/admin/listallusers');


    }

    public function addsliderAction()
    {
        $form=new Application_Form_Sliderform();
        $form->setAttrib('enctype', 'multipart/form-data');
                $this->view->form = $form;

        $request = $this->getRequest();
         if($request->isPost()){
            if($form->isValid($request->getPost())){
                        $location = $form->image->getFileName();
                                $request->setParam('image', $location);

        $values = $form->getValues();
  if (!$form->image->receive()) {
            print "Upload error";
        }
            $slider_model=new Application_Model_Slider();
            $slider_model-> addNewSlider($request->getParams());
            $this->redirect('/admin/addslider');
  }
}
}

    public function addcatAction()
    {
        $category_form = new Application_Form_Categoryform();
        $this->view->cat_form = $category_form;
           $request = $this->getRequest();
        if($request->isPost()){
        if($category_form->isValid($request->getPost())){
        $cat_model = new Application_Model_Category();
        $cat_model->addCat($request->getParams());
        $this->redirect('/admin/listallcats');
        }
    }
    }

    public function listallcatsAction()
    {
       $category_model= new Application_Model_Category();
       $this->view->categories = $category_model->listAll();
    }

    public function editcatAction()
    {
        $cat_form = new Application_Form_Categoryform();
        $cat_id = $this->_request->getParam('cid');
        $cat_model = new Application_Model_Category();
        $cat_data = $cat_model->getCat($cat_id);
        $cat_form->populate($cat_data);
        $this->view->cat_form = $cat_form;
        $request = $this->getRequest();
        if($request-> isPost()){
        if($cat_form-> isValid($request-> getPost())){
        $cat_model-> updateUser ($cat_id,$request->getPost());
        $this->redirect('/admin/listallcats ');

        }
        }
    }

    public function deletecatAction()
    {
        $cat_model = new Application_Model_Category();
        $cat_id = $this->_request->getParam('cid');
        $cat_model->delete("id=$cat_id");
        $this->redirect("/admin/listallcats");
    }

    public function deletefromsliderAction()
    {

      $slider_model = new Application_Model_Slider();
      $s_id = $this->_request->getParam('sid');
      $slider = $slider_model->deletefromslider($s_id);
      $this->redirect("/admin/listfromslider");
    }

    public function listfromsliderAction()
    {

      $slider_model = new Application_Model_Slider();

        $this->view->slider_admin = $slider_model->listfromslider();
    }

    public function detailsliderAction()
    {

        $slider_model = new Application_Model_Slider();        
       $s_id = $this->_request->getParam('sid');
       $slider = $slider_model->sliderDetails($s_id);
        $this->view->slider_data = $slider;
    }

    public function editsliderAction()
    {


         $form = new Application_Form_Sliderform();
         $id = $this->_request->getParam('sid');
        $slider_model = new Application_Model_Slider();
        $slider_data = $slider_model->sliderDetails($id);
        $form->populate($slider_data);
        $this->view->slider_form = $form;
        $request = $this->getRequest();
            if($request->isPost()){
                if($form->isValid($request->getPost())){



                        $location = $form->image->getFileName();
                 $request->setParam('image', $location);
                 $slider_model-> updateSlider($id,$request->getParams());
                                  $this->redirect('/admin/listfromslider');



        $values = $form->getValues();
            if (!$form->image->receive()) {
                print "Upload error";
        }
            $slider_model=new Application_Model_Slider();
             $slider_model-> updateSlider($id,$request->getParams());
                 $this->redirect('/admin/listfromslider');
        }
        }
    }

     public function addproductAction()
    {
        $form = new Application_Form_Productform();
        $form->setAttrib('enctype', 'multipart/form-data');
        $this->view->productForm = $form;
        $request = $this->getRequest();
        if($request->isPost()){
        if($form->isValid($request->getPost())){
        $product_model = new Application_Model_Product();
        
        $location = $form->picture->getFileName();

        $request->setParam('picture', $location);
        
        $values = $form->getValues();
 

        if (!$form->picture->receive()) {
            print "Upload error";
        }
        $product_model->addNewProduct($request->getParams());
        $this->redirect('/shop/listproducts');
                                                }
                              }
        
        
        
        
    }

    public function listproductsAction()
    {   
        $product_model = new Application_Model_Product();
        
        $this->view->products = $product_model->listProducts();
        
        
    }
    
    public function productdetailsAction()
    {
      $product_model = new Application_Model_Product();
      $comment_form = new Application_Form_Commentform();
      $this->view->comment_form = $comment_form;
      $p_id = $this->_request->getParam('pid');
      $product = $product_model->productDetails($p_id);
      $this->view->comments = $product_model->listProductcomments($p_id);
      $this->view->product = $product[0];
      $this->view->allcoments = $product_model->SelectionComment($p_id);
      $request = $this->getRequest();
      if($request->isPost()){
      if($comment_form->isValid($request->getPost())){
      $comment_model = new Application_Model_Comment();
      $pid=$request->getParam('pid');
      $request->setParam('product', $pid);
      
      
      $request->setParam('customer_id', 1);

      
      $comment_model->addComment ($request->getParams());
      $this->redirect("/shop/productdetails/pid/$pid");
      
      
      }
      }
    }

     public function productstaticAction()
    {

          $product_model = new Application_Model_Product();
          $id = $this->_request->getParam('pid');
        
        $product = $product_model->productDetails($id);
        $static = $product_model->sql($id);

        $this->view->product = $product[0];
        $this->view->statics = $static;
    }

    public function deleteproductAction()
    {
      $product_model = new Application_Model_Product();
      $p_id = $this->_request->getParam('pid');
      $user = $product_model->deleteProduct($p_id);
      $this->redirect("/shop/listproducts");
    }

    public function editproductAction()
    {
        $form = new Application_Form_Productform ();
        $product_model = new Application_Model_Product ();
        $id = $this->_request->getParam('pid');
        $product_data = $product_model->productDetails($id)[0];
        $form->populate($product_data);
        $this->view->product_form = $form;
        $request = $this->getRequest();
        if($request->isPost()){
        if($form->isValid($request->getPost())){
        $product_model->updateProduct($id, $_POST);
        $this->redirect('/shop/listproducts');
        }
        }

    }
    
}
        




     
       




























