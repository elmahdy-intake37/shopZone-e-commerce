<?php

class Application_Form_Signup extends Zend_Form
{

    public function init()
    {
        $this->setMethod('POST');
        $name=new Zend_Form_Element_Text('name');
        $name->setLabel('Name: ');
        $name->setRequired();
        $name->addValidator('StringLength', false, Array(3,10));
		$name->addFilter('StringTrim');
        $name->setAttribs(array('class' =>'form-control','placeholder' => 'example. Bassant'));


        $email=new Zend_Form_Element_Text('email');
        $email->setLabel('Email: ');
        $email->setRequired();
        $email->setAttribs(array('class' =>'form-control','placeholder' => 'exampleb@email.com'));

        $password=new Zend_Form_Element_Password('password');
        $password->setLabel('Password: ');
        $password->setRequired();
        $password->setAttribs(array('class'=>'form-control'));

		$address=new Zend_Form_Element_Text('address');
        $address->setLabel('Address: ');
        $address->setRequired();
        $address->addValidator('StringLength', false, Array(3,20));
		$address->addFilter('StringTrim');
        $address->setAttribs(array('class' =>'form-control','placeholder' => 'example. glim,alexandria'));

        $type = new Zend_Form_Element_Radio('type');
            $type->addMultiOptions(array(
                    'user' => 'User',
                    'shop' => 'Shop' 
                        ))
            ->setSeparator('');


		$submit=new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(array('class'=>'btn btn-success'));


        $reset=new Zend_Form_Element_Reset('reset');
        $reset->setAttribs(array('class'=>'btn btn-danger'));



$this->addElements(array($name,$email,$address,$password,$type,$submit,$reset));

    }


}

