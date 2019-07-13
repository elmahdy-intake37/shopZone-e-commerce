<?php

class Application_Model_Rate extends Zend_Db_Table_Abstract
{
    protected $_name  ='rate';
    
    function addNewRate($newrate)
	{
	
        $user_id = $_SESSION["Zend_Auth"]["storage"]->id;
       
        $select=$this->select()
                ->from('rate','*')
                ->where('product= ?',$newrate['product'])
                ->where('customer= ?',$user_id);
        $stmt = $select->query();
        $result = $stmt->fetchAll();
        if (empty($result)){
        $row = $this->createRow();
        $row->customer = $user_id;
	$row->product = $newrate['product'];
	$row->rate = $newrate['star'];
        $row->save();
        
        $product_model = new Application_Model_Product();
        $data=array('total_users_rate'=>new Zend_Db_Expr('total_users_rate + 1'),'total_rates'=>new Zend_Db_Expr('total_rates +'.$newrate['star']));
        $where='id='.$newrate['product'];
        $product_model->update($data, $where);
        
         }
         
         else {
        $old = $this->select()
                ->from('rate','rate')
                ->where('customer=?',$user_id)
                ->where('product=?',$newrate['product']);
        $old = $old->query();
        $old = $old->fetchAll();
        $old = $old[0]['rate'];
        $new =  $newrate['star'] - $old ; 
        $data=array('rate'=>new Zend_Db_Expr($newrate['star']));
        $where='customer='.$user_id;
        $this->update($data, $where);
        $product_model = new Application_Model_Product();
        $data=array('total_rates'=>new Zend_Db_Expr('total_rates +'.$new));
        $where='id='.$newrate['product'];
        $product_model->update($data, $where);
             
             
         }
      
	}
}

