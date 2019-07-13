<?php

class Application_Model_Category extends Zend_Db_Table_Abstract
{
 protected $_name  ='category';

  function listAll(){
    return $this->fetchAll()->toArray();
  }

  function addCat($catData)
  {
      
  $row = $this->createRow();
  $row->name = $catData['name'];
  $row->save();
  }
  function getCat($cat_id)
  {
  return $this->find($cat_id)->toArray()[0];
  }
public function ListCategory($id)
    {
      $sql=$this->select()
      ->from(array('p'=>"product"))
      ->joinInner(array("C"=>"category"), "p.category=C.id",array("name as category_name"))
      ->where("p.category= $id")
      ->setIntegrityCheck(false);
      $query=$sql->query();
      $result=$query->fetchAll();
      return $result;
 
    }
}

