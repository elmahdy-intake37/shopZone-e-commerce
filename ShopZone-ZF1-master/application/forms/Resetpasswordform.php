<?php

class Application_Form_Resetpasswordform extends Zend_Form
{

    public function init()
    {
       $this->setMethod('POST');
        $email=new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
              ->setAttribs(array('class'=>'form-control','placeholder'=>'example.Ahmed','type'=>'email'))
              ->setRequired();
        $email->addValidator('StringLength',false,Array(4,40));
         $email->addFilter('StringTrim');
    
         

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'btn btn-success');
        $reset = new Zend_Form_Element_Reset('Reset');
        $reset->setAttrib('class', 'btn btn-danger');
        $this->addElements(array($email,$submit,$reset));

    }


}

