<?php

class Application_Form_Addtocart extends Zend_Form
{

  public function init()
  {
    $this->setMethod('POST');

    $qty=new Zend_Form_Element_Text('quantity');
    $qty->setLabel('Quantity')
          ->setRequired(true)
          ->setAttrib('class','form-control');

    $submit = new Zend_Form_Element_Submit('Add to ShopCard');
    $submit->setAttrib('class', 'btn btn-success');

    $this->addElements(array($qty,$submit));

  }


}
