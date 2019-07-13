<?php

class Application_Form_Changepasswordform extends Zend_Form
{

    public function init()
    {
        $this->setMethod('POST');
        $password1=new Zend_Form_Element_Password('password');
        $password1->setLabel('Password: ');
        $password1->setRequired();
        $password1->setAttribs(array('class'=>'form-control'));
        
        $password2=new Zend_Form_Element_Password('password2');
        $password2->setLabel('Repeat Password: ');
        $password2->setRequired();
        $password2->setAttribs(array('class'=>'form-control'));

        

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'btn btn-success');
        $reset = new Zend_Form_Element_Reset('Reset');
        $reset->setAttrib('class', 'btn btn-danger');
        $this->addElements(array($password1,$password2,$submit,$reset));

    }


}

