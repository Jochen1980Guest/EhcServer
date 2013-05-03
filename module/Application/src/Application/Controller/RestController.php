<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class RestController extends AbstractRestfulController{
	
	public function indexAction(){
		return  new ViewModel();
	}
	
	public function getList() {
		// HTTP GET 
		return new JsonModel(array(
            array('name' => 'test'),
            array('name' => 'getlist')
        ));
	}
	
	public function get($id) {
		// HTTP GET id
		return new JsonModel(array(
            array('name' => 'test'),
            array('name' => 'get')
        ));
	}
	
	public function create($data) {
		// HTTP PUT
		return new JsonModel(array(
            array('name' => 'test'),
            array('name' => 'create')
        ));
	}
	
	public function update($id, $data) {
		// HTTP PUT id
		return new JsonModel(array(
            array('name' => 'test'),
            array('name' => 'update')
        ));
	}
	
	public function delete($id) {
		// HTTP DELETE id
		return new JsonModel(array(
            array('name' => 'test'),
            array('name' => 'delete')
        ));
	}
}
