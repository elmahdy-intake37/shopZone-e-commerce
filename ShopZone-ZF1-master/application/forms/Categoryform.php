<?php

class Application_Form_Categoryform extends Zend_Form
{

    public function init()
    {
       $this->setMethod('POST');
       $name = new Zend_Form_Element_Text('name');
       $name->setLabel('Category Name: ')
            ->setAttribs(Array(
        'placeholder'=>'Example: Clothes',
        'class'=>'form-control'
        ));

        $name->setRequired();
        $name->addValidator('StringLength', false, Array(4,150));
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'btn btn-success');
        $reset = new Zend_Form_Element_Reset('Reset');
        $reset->setAttrib('class', 'btn btn-danger');
        $this->addElements(array($name,$submit,$reset));

    }


}

