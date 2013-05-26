<?php

namespace Application\Filter;

use Zend\InputFilter\InputFilter;

class ContactFilter extends InputFilter
{
    public function __construct(){
        $this->add(array(
        		'name' => 'email',
        		'required' => true,
        		'validators' => array( array('name' => 'EmailAddress')),
        		));
        $this->add(array(
        		'name' => 'name',
        		'required' => true,
        		'filters' => array(array('name' => 'StringTrim')),
        		));
    }
}
