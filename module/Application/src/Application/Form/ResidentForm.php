<?php
/**
 * ZF2 Buch Kapitel 13
 * 
 * Das Buch "Zend Framework 2 - Von den Grundlagen bis zur fertigen Anwendung"
 * von Ralf Eggert ist im Addison-Wesley Verlag erschienen. 
 * ISBN 978-3-8273-2994-3
 * 
 * @package    Pizza
 * @author     Ralf Eggert <r.eggert@travello.de>
 * @copyright  Alle Listings sind urheberrechtlich geschützt!
 * @link       http://www.zendframeworkbuch.de/ und http://www.awl.de/2994
 */

/**
 * namespace definition and usage
 */
namespace Application\Form;

use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Login form
 * 
 * Handles the login form
 * 
 * @package    Pizza
 */
class ResidentForm extends Form {
    
	public function __construct(){
    	parent::__construct('residentForm');
    	$this->setAttribute('action', '/index/test');
    	$this->setAttribute('method', 'post');
    	$this->setInputFilter(new \Application\Filter\ResidentFilter());
    	$this->add(array(
    			'name' => 'name',
    			'attributes' => array(
    					'type' => 'text',
    					'id' => 'name'),
    	));
    	$this->add(array(
    			'name' => 'room',
    			'attributes' => array(
    					'type' => 'text',
    					'id' => 'room'),
    	));
    	$this->add(array( // submit button;
    			'name' => 'submit',
    			'attributes' => array(
    					'type' => 'submit',
    					'value' => 'Abspeichern' ),
    	));
    }
}
