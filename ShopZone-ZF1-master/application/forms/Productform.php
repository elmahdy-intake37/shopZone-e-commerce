<?php

class Application_Form_Productform extends Zend_Form
{

    public function init()
    {

        $this->setMethod('POST');
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Product Name: ');
        $name->setAttribs(Array(
        'placeholder'=>'Example: Apple',
        'class'=>'form-control'
        ));

        $name->setRequired();
        $name->addValidator('StringLength', false, Array(4,20));
        $name->addFilter('StringTrim');


        $description = new Zend_Form_Element_Text('description');
        $description->setLabel('Product description: ');
        $description->setAttribs(Array(
        'placeholder'=>'Example: ......',
        'class'=>'form-control'
        ));
        $description->setRequired();
        $description->addValidator('StringLength', false, Array(4,));


         $ar_name = new Zend_Form_Element_Text('ar_name');
        $ar_name->setLabel('اسم المنتج: ');
        $ar_name->setAttribs(Array(
        'placeholder'=>'Example: Apple',
        'class'=>'form-control'
        ));

        
        $ar_name->addValidator('StringLength', false, Array(4,20));
        $ar_name->addFilter('StringTrim');


        $description_ar = new Zend_Form_Element_Text('description_ar');
        $description_ar->setLabel('موصفات المنتج: ');
        $description_ar->setAttribs(Array(
        'placeholder'=>'Example: ......',
        'class'=>'form-control'
        ));
       
        $description_ar->addValidator('StringLength', false, Array(4,));
        




        $price = new Zend_Form_Element_Text('price');
        $price->setLabel('Product price: ');
        $price->setAttribs(Array(
        'placeholder'=>'Example: 1000',
        'class'=>'form-control'
        ));
        $price->setRequired();
        $price->addValidator('StringLength', false, Array(1,10));
        $price->addFilter('StringTrim');

        $picture = new Zend_Form_Element_File('picture');
        $picture->setLabel('Product picture: ')
                ->setDestination('imgs')
                ->setValueDisabled(true)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, 1024000)
                ->addValidator('Extension', false, 'jpg,png,gif,jpeg');
        $picture->setAttribs(Array(
        'placeholder'=>'Example: 1000',
        'class'=>'form-control'
        ));

        $quantity = new Zend_Form_Element_Text('quantity');
        $quantity->setLabel('Product quantity: ');
        $quantity->setAttribs(Array(
        'placeholder'=>'Example: 1000',
        'class'=>'form-control'
        ));
        $quantity->setRequired();
        $quantity->addValidator('StringLength', false, Array(1,10));
        $quantity->addFilter('StringTrim');


        $cat_obj = new Application_Model_Category();

        $allcats = $cat_obj->listAll();
        $cat_id = new Zend_Form_Element_Select('category');
        $cat_id->setLabel('category : ');
        $cat_id->setAttrib('class', 'form-control');

        foreach($allcats as $key=>$value)
        {
         $cat_id->addMultiOption($value['id'], $value['name']);
        }



        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'btn btn-success');
        $reset = new Zend_Form_Element_Reset('Reset');
        $reset->setAttrib('class', 'btn btn-danger');
        $this->addElements(array($name,$description,$ar_name,$description_ar,$picture,$quantity,$price,$cat_id,$submit,$reset));




    }


}
