<?php

namespace Application\Form;

use Zend\Form\Form;

class ContactForm extends Form {
	
    public function __construct(){
        parent::__construct('contactForm');
        $this->setAttribute('action', '/index/contact'); // verarbeitende Action
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new \Application\Filter\ContactFilter());
        $this->add(array( // field 'Ihr Name';
        		'name' => 'name', 
        		'attributes' => array(
        			'type' => 'text',
        			'id' => 'name'),
//         		'options' => array(
//         			'label' => 'Name: '),
        		));
        $this->add(array( // field 'Ihre E-Mail';
        		'name' => 'email', 
        		'attributes' => array(
        			'type' => 'email',
        			'id' => 'email'),
//         		'options' => array(
//         			'label' => 'Mail: '),
        		));
       $this->add(array( // field 'Ihre Nachricht';
       			'name' => 'message',
       			'attributes' => array(
       				'type' => 'textarea',
       				'id' => 'message'),
//        			'options' => array(
//        				'label' => 'Nachricht: '),
       			));
       $this->add(array( // submmit button;
        		'name' => 'submit', 
        		'attributes' => array(
        			'type' => 'submit',
        			'value' => 'Abschicken' ),
        		));
    }
}
