<?php

class Application_Model_Coupon extends Zend_Db_Table_Abstract
{
    protected $_name  ='coupon';
        public function addCoupon($data){

  		$coupon['code']=$data['code'];
  		$coupon['discount']=$data['discount'];
                $coupon['customer']=$data['customer'];
  		$row=$this->createRow($coupon);
  		$row->save();
}
       public function coupon($code)
	{
		return $this->find($code)->toArray();
	}

function checkdis($cpn,$uid)
{
  $db=Zend_Db_Table::getDefaultAdapter();
  $select=new Zend_Db_Select($db);
  $select->from('coupon','*')
          ->where("customer=$uid and code='$cpn'");
  $stmt = $select->query();
  $result = $stmt->fetchAll();
  
 
}


}
