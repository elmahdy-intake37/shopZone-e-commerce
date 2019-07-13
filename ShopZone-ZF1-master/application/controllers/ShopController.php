<?php

class ShopController extends Zend_Controller_Action
{

    private $product_model = null;

    public function init()
    {
     $product_model = new Application_Model_Product();
    
       //for arabic
       $request= $this->getRequest()->getParam('ln');
       if(empty($request)){
         $this->language= new Zend_Session_Namespace('language');
          $this->language->type = isset($this->language->type)?$this->language->type:"En";
      }
      else{
          $this->language= new Zend_Session_Namespace('language');
          $this->language->type = $request ;
      
      }

          $this->view->language=$this->language;


    }

    public function indexAction()
    {
                $product_model = new Application_Model_Product();
        $user_id=   $_SESSION["Zend_Auth"]["storage"]->id;
        
        $this->view->products = $product_model->listCusomerProducts($user_id);
        
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
        $user_id=   $_SESSION["Zend_Auth"]["storage"]->id;
        if($this->getRequest()->getParam('cid')){
            $cid=$this->getRequest()->getParam('cid');
            $this->view->products = $product_model->listProdCat($cid);
        } 
        else{
        $this->view->products = $product_model->listCusomerProducts($user_id);
        }
       
        
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
      
      
      $request->setParam('customer_id', $_SESSION["Zend_Auth"]["storage"]->id);

      
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
        $product_data = $product_model->productDetails($id);
        $form->populate($product_data[0]);
        $this->view->product_form = $form;
        $request = $this->getRequest();
        if($request->isPost()){
        if($form->isValid($request->getPost())){
        $product_model->updateProduct($id, $_POST);
        $this->redirect('/shop/listproducts');
        }
        }

    }

    public function editofferAction()
    {
        $form = new Application_Form_Offer ();
        $offer_model = new Application_Model_Offer ();
        $id = $this->_request->getParam('oid');
        $offer_data = $offer_model->offerDetails($id)[0];
        $form->populate($offer_data);
        $this->view->offer_form = $form;
        $request = $this->getRequest();
        if($request->isPost()){
        if($form->isValid($request->getPost())){
        $offer_model->updateOfferData($id, $_POST);
        $this->redirect('/shop/listoffers');
        }
        }

    }

    public function addofferAction()
    {
        $form = new Application_Form_Offer();

        $this->view->offer_form = $form;
        if (!empty($this->_request->getParam('pid'))){
        $this->view->p_id=$this->_request->getParam('pid');
        }
      
        $request = $this->getRequest();
        if($request->isPost())
        {
            if($form->isValid($request->getPost()))
            {
               
                $offer_model = new Application_Model_Offer();
                $offer_model->addNewOffer($request->getParams());
                $this->redirect('/shop/listoffers');
            }
        }
    
    }

    public function offerdetailsAction()
    {
      $offer_model = new Application_Model_Offer();
      $id = $this->_request->getParam('oid');
      
      $offer = $offer_model->offerDetails($id);
      $this->view->offer = $offer[0];
    }

    public function deleteofferAction()
    {
      $offer_model = new Application_Model_Offer();
      $id = $this->_request->getParam('oid');
      $user = $offer_model->deleteOffer($id);
      $this->redirect("/shop/listoffers");
    }

    public function listoffersAction()
    {
        $offer_model = new Application_Model_Offer();
        
        $this->view->offers = $offer_model->getAllOffers();
   
    }

    public function selectcommentAction($id)
    {
        

         
                $comment_model = new Application_Model_Product();
        $comment_id = $this->_request->getParam("pid");
        $this->view->comment = $comment_model->SelectionComment($comment_id);

       
        

    }


}


