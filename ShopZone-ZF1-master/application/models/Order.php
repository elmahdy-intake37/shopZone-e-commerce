<?php

class Application_Model_Order extends Zend_Db_Table_Abstract
{
    protected $_name  ='order';
    public function addorder($uid,$total){
        $order['customer']=$uid;
  		$order['total_price']=$total;
  		$row=$this->createRow($order);
  		$row->save();
        
    }

}

