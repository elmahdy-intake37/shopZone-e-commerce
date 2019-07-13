<?php

class Application_Model_Offer extends Zend_Db_Table_Abstract
{

	protected $_name='offer';


	function getAllOffers()
		{
		return $this->fetchAll()->toArray();
		}


	function addNewOffer($newOffer)
	{
        
	$row = $this->createRow();
	$row->offer_per = $newOffer['offer_per'];
	$row->offer_start = $newOffer['offer_start'];
	$row->offer_end = $newOffer['offer_end'];
        $row->product_id=$newOffer['pid'];
	$row->save();
        
        $select = $this->select()
                ->from('offer','id')
                ->where('product_id=?',$newOffer['pid']);
        $stmt = $select->query();
        $result = $stmt->fetchAll();
        
          $product_model = new Application_Model_Product();
            $data = array(
                'offer'=>$result[0]['id'],
                    );
                $where = $product_model->getAdapter()->quoteInto('id = ?', $newOffer['pid']);
                $product_model->update($data, $where);
        
	}


	public function deleteOffer($id)
		{
		$this->delete("id=$id");
		}

	function offerDetails($id)
		{
		return $this->find($id)->toArray();
		}

	function updateOfferData($id,$formData)
	{


	  $offerData1['offer_per']=$formData['offer_per'];
	  $offerData1['offer_start']=$formData['offer_start'];
	  $offerData1['offer_end']=$formData['offer_end'];
	  $this->update($offerData1,"id=$id");
	}	
        
        function productInfo($id){
            $product_model = new Application_Model_Product();
            $select = $product_model->select()
                    ->from('product','name')
                    ->where('offer=?',$id);
            $stmt = $select->query();
            $result = $stmt->fetchAll();
            return $result;
        }
}

