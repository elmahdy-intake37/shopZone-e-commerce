<?php

class Application_Form_Sliderform extends Zend_Form
{

    public function init()
    {
        $this->setMethod('POST');
       $image = new Zend_Form_Element_File('image');
        $image->setLabel('SLider image: ')
                ->setDestination('imgs')
                ->setValueDisabled(true)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, 1024000)
                ->addValidator('Extension', false, 'jpg,png,gif');
        $image->setAttribs(Array(
        'placeholder'=>'Example: 1000',
        'class'=>'form-control'
        ));

        $url = new Zend_Form_Element_Text('url');
        $url->setLabel('Image url: ');
        $url->setAttribs(Array(
        'placeholder'=>'Example: ......',
        'class'=>'form-control'
        ));
        $url->setRequired();
        $url->addValidator('StringLength', false, Array(4,));
    

   $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'btn btn-success');
        $reset = new Zend_Form_Element_Reset('Reset');
        $reset->setAttrib('class', 'btn btn-danger');
        $this->addElements(array($image,$url,$submit,$reset));
}

}