<?php

class Application_Model_Slider extends Zend_Db_Table_Abstract
{
    protected $_name  ='slider';

     public function addNewSlider($sliderData){

        $row = $this->createRow();
        $row->image =$sliderData['image'];
    
        $row->url = $sliderData['url'];
        $row->save();
    }
       public function slider()
    {
        $sql=$this->select()
        ->from("slider",array('image','url'))
       
        ->limit(10, 0)
        ->setIntegrityCheck(false);

        $query=$sql->query();
       
        $result=$query->fetchAll();
        return $result;
    }
     public function deletefromslider($id){
        $this->delete("id=$id");
    }

    public function listfromslider(){
        return $this->fetchAll()->toArray();
       
    }


     public function sliderDetails($id){

        return $this->find($id)->toArray()[0];
    }
    
    public function updateSlider($id,$sliderData)
    {

     $sliderNewData['image']=$sliderData['image'];
      $sliderNewData['url']=$sliderData['url'];
      
      $this->update($sliderNewData,"id=$id");

    }


}

