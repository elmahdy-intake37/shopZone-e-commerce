<?php
use Zend\Db\Sql\Sql;
class Application_Model_Cartitem extends Zend_Db_Table_Abstract
{
    protected $_name  ='cartitem';

    function addProduct($userData)
    {
      $row= $this->createRow();
      $row->quantity=$userData['quantity'];
      $row->customer=$userData['customer'];
      $row->product=$userData['product'];
      $row->save();
    }
    function listcartitems()
    {
      return $this->fetchAll()->toArray();
    }
    function selectprice($prdid)
    {

      $product = new Application_Model_Product();
      $row = $product->fetchRow($product->select()->where('id = ?', $prdid)
                                                  ->where());

      // Get the column/value associative array from the Row object
      $rowArray = $row->toArray();

      // Now use it as a normal array
      foreach ($rowArray as $column => $value) {
          if ($column =='price') {
            return $value;
          }
      }
    }

      function selectoffer($id)
      {
        $uid=$_SESSION["Zend_Auth"]["storage"]->id;
        $sql=$this->select()
                ->setIntegrityCheck(FALSE)
        ->from(array('c' => 'cartitem','p' => 'product'), array('p.name','c.quantity','p.price','p.id','p.offer'))
               ->join(array('p' => 'product'), 'p.id=c.product', array('p.name','p.price'))
               ->where('c.customer = ?', $id)
               ->order('p.id','ASC')
        ->setIntegrityCheck(false);
        $query=$sql->query();
        $result=$query->fetchAll();
        
        return $result;
      }
      public function deletecart($uid){
       return $this->delete("customer=$uid");   
      }
              function deleteItem($pid)
      {
        return $this->delete("product=$pid");
      }
      
      function checkOffer($list){
          $db=Zend_Db_Table::getDefaultAdapter();
                    $select=new Zend_Db_Select($db);
                   $count = count($list);
          for ($i=0;$i<$count;$i++){
              if($list[$i]['offer'] == NULL){
                  $list[$i]['offer'] = 0;
              }
              else{
                  
                    $select->from('offer','offer_per')
                            ->where('id= ?',$list[$i]['offer']);
                    $stmt = $select->query();
                    $result = $stmt->fetchAll();
                    $list[$i]['offer'] = $result[0]['offer_per'];
                    
                  
              }       
          }
          return $list;
      }
      public function updatecart($customer,$product,$quen){
        $quan['quantity']=$quen;
        $where['product = ?'] = $product;
        $where['customer = ?'] = $customer;
        $this->update($quan, $where);
      }
}
