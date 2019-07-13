<?php

class Application_Model_Comment extends Zend_Db_Table_Abstract
{
    protected $_name  ='comment';
     
    
    public function addComment($formData){
                $commentData['customer_id']=$formData['customer_id'];
  		$commentData['product']=$formData['product'];
  		$commentData['comment']=$formData['comment'];
  		$row=$this->createRow($commentData);
  		$row->save();
    }

}

