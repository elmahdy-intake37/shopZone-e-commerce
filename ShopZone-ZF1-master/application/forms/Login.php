<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setMethod('POST');
        $name=new Zend_Form_Element_Text('email');
        $name->setLabel('Email')
              ->setAttribs(array('class'=>'form-control','placeholder'=>'example.Ahmed'))
              ->setRequired();
   
        $password=new Zend_Form_Element_Password('pass');
        $password->setLabel('PassWord')
                ->setAttribs(array('class'=>'form-control'))
                ->setRequired();
   
        $submit=new Zend_Form_Element_Submit('Login');
        $submit->setAttribs(array('class'=>'btn btn-success'));

        $this->addElement($name);
        $this->addElement($password);
        $this->addElement($submit);
}
}
