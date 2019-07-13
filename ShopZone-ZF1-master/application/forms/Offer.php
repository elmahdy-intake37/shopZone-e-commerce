<?php

class Application_Form_Offer extends Zend_Form
{

    //ZendX_JQuery_Form
    public function init()
    {

    	$this->setMethod('POST');
   

    	$offer_per = new Zend_Form_Element_Text('offer_per');
    	$offer_per->setLabel('Offer percentage: ');
    	$offer_per->setRequired();
    	$offer_per->setAttribs(Array('placeholder' => 'Example:0.10', 'class' => 'form-control'));

    	$offer_start = new Zend_Form_Element_Text('offer_start');
    	$offer_start->setRequired();
    	$offer_start->setAttribs(Array('placeholder' => 'Example:2000-12-31', 'class' => 'form-control','type' => 'date'));

    	$offer_end = new Zend_Form_Element_Text('offer_end');
    	$offer_end->setRequired();
    	$offer_end->setAttribs(Array('placeholder' => 'Example:2000-12-31', 'class' => 'form-control'));



    	$submit= new Zend_Form_Element_Submit('submit');
		$submit->setAttribs(array('class'=>'btn btn-success'));

		$reset= new Zend_Form_Element_Reset('reset');
		$reset->setAttribs(array('class'=>'btn btn-danger'));

                
    	$this->addElements(array(
	 $offer_per,
	 $offer_start,
	 $offer_end,
	 $submit,
	 $reset
	));

    }


}

