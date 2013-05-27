<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Service\RoomService;

use Zend\Db\Adapter\Adapter;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Db\TableGateway\TableGateway;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class RestController extends AbstractRestfulController{
	
	protected $userService;
	protected $roomService;
	protected $residentService;
	
	public function setUserService(UserService $userService){
		$this->userService = $userService;
		return $this;
	}
	
	public function getUserService(){
		return $this->userService;
	}
	
	public function setRoomService(RoomService $roomService){
		$this->roomService = $roomService;
		return $this;
	}
	
	public function getRoomService(){
		return $this->roomService;
	}
	
	public function setResidentService(ResidentService $residentService){
		$this->residentService = $residentService;
		return $this;
	}

	public function getResidentService(){
		return $this->residentService;
	}

	public function getList() {
		// HTTP GET
		$db = $this->getServiceLocator()->get('db');
		$roomTable = new TableGateway('room', $db);
		$this->setRoomService(new RoomService($roomTable));
		
// 		$rows = $this->getRoomService()->fetchList();
// 		//$rows = $tempService->fetchSingleById(array('id', 1));
// 		foreach($rows as $row){
// 			Debug::dump($row->getArrayCopy());
// 		    		$content .= "<p>";
// 		    		$content .= $row['username'];
// 		    		$content .= "</p>";
// 		    	}
		return new JsonModel(array(
			'data' => $this->getRoomService()->fetchList(),
        ));
	}
	
	public function get($id) {
		//Debug::dump("get-id");
		// HTTP GET id
		// Call: http://ehcserver.localhost/rest/4
		// Testen via RESTClient Firefox-Addon!
		return new JsonModel(array(
            array('name' => 'test' . $id),
            array('name' => 'get')
        ));
	}
	
	public function create($data) {
		// HTTP POST
		//$id = $this->getPizzaService()->add($data);
		$id = "5";
		return new JsonModel(array(
            array('name' => 'test' . $id),
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
