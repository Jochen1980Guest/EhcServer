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

class RestController extends AbstractRestfulController{
	
	public function getList() {
		return array("users" => array());
	}
	
	public function get($id) {
		return array("id" => $id);
	}
	
	public function create($data) {
		return array("created" => "yes");
	}
	
	public function update($id, $data) {
		return array("updated" => "yes");
	}
	
	public function delete($id) {
		return array("deleted" => $i);
	}
}
