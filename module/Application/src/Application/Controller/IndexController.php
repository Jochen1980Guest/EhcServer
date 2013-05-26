<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Entity\ResidentEntity;
use Application\Entity\RoomEntity;
use Application\Entity\UserEntity;
use Application\Form\LoginForm;
use Application\Form\LogoutForm;
use Application\Form\ResidentForm;
use Application\Form\RoomForm;
use Application\Service\ResidentService;
use Application\Service\RoomService;
use Application\Service\UserService;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Acl;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController{
	
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
	
    public function indexAction(){
    	// Check Session
    	// db interaction
    	$db = $this->getServiceLocator()->get('db');
    	$query = "SELECT * FROM widget WHERE name LIKE 'home';";
    	$res = $db->query(
    		$query, Adapter::QUERY_MODE_EXECUTE
    	);
    	foreach($res as $row){
    		$header = $row['header'];
    		$content = $row['content'];
    		//Debug::dump($row);
    	}
    	
    	// View-Variable bestuecken
        return new ViewModel(
        	array(
        		'header' => $header,
        		'content' => $content
        	) 
        );
    }

	public function historieAction(){
        return new ViewModel();
    }
    
    public function loginAction(){
    	// Session erkunden
    	$userSession = new Container('user');
    	$username = $userSession->username;
    	 
    	// set variables for default layout
    	$this->layout()->pageTitle 		= "SKP-Technik | Login";
    	$this->layout()->navBreadcrumb 	= "Ort: Login";
    	 
    	// initialize important variables
    	$form = "";
    	$header = "";
    	$message = "";
    	$isPost = "";
    	$postData = "";
    	$isLoginTry = "";
    	$isValidLoginTry = "";
    	$isLogoutTry = "";
    	$isValidLogoutTry = "";
    	$validData = "";
    	 
    	// create forms
    	$loginForm = new LoginForm();
    	$logoutForm = new LogoutForm();
    	 
    	// relevant flags
    	$isPost = $this->getRequest()->isPost();
    	if ($isPost){
    		$postData = $this->getRequest()->getPost();
    		if ($postData['submit'] == "Einloggen"){
    			$isLoginTry = true;
    			$form = new LoginForm();
    			$form->setData($postData);
    			if ($form->isValid()){
    				$isValidLoginTry = true;
    			}
    		}
    		if ($postData['submit'] == "Ausloggen"){
    			$isLogoutTry = true;
    			$form = new LogoutForm();
    			$form->setData($postData);
    			if ($form->isValid()){
    				$isValidLogoutTry = true;
    			}
    		}
    	}
    	 
    	// valid login
    	if ($isValidLoginTry == true){
    		$validData = $form->getData();
    		if ($validData['name'] == "skp" && $validData['pass'] == "skp") {
    			$header = "Eingeloggt";
    			$userSession = new Container('user');
    			$userSession->username = 'skp';
    			$message = "<p style='color: orange;'>Willkommen " . $userSession->username . "!</p>";
    			$message .= "<p style='font-weight: bold; color: orange;'>Menu:</p>";
    			$message .= "<p><a href='http://devzf2.jochen-bauer.net/blog-admin'>Nachrichten verwalten</a></p>";
    			$form = new LogoutForm();
    			return new ViewModel(
    					array(
    							'widgetHeader' 		=> $header,
    							'form' 				=> $form,
    							'message'			=> $message
    					));
    		} else { // LoginVersuch mit falschen Daten
    			$header = "Einlogversuch gescheitert!";
    			$message = "";
    			$form = new LoginForm();
    			return new ViewModel(
    					array(
    							'widgetHeader' 		=> $header,
    							'form' 				=> $form,
    							'message'			=> $message
    					));
    		}
    	}
    	 
    	// valid logout
    	if ($isValidLogoutTry == true){
    		$validData = $form->getData();
    		// Session zerstoeren
    		$username = "";
    		$userSession->username = "";
    		$header = "<span style='text-align: center; color: orange;'>Ausloggen erfolgreich</span>";
    		$message = "";
    		$form = new LoginForm();
    		return new ViewModel(
    				array(
    						'widgetHeader' 		=> $header,
    						'form' 				=> $form,
    						'message'			=> $message
    				));
    	
    	}
    	 
    	// default behaviour already logged in
    	if ($username != ""){
    		$header = "<span style='text-align: center; color: orange;'>Eingeloggt</span>";
    		$message = "<p>Willkommen " . $userSession->username . "!</p>";
    		$message .= "<p style='font-weight: bold; color: orange;'>Menu</span></p>";
    		$message .= "<p><a href='http://devzf2.jochen-bauer.net/blog-admin'>Nachrichten verwalten</a></p>";
    		$form = new LogoutForm();
    		return new ViewModel(
    				array(
    						'widgetHeader' 		=> $header,
    						'form' 				=> $form,
    						'message'			=> $message
    				));
    	}
    	 
    	// default behaviour not logged in - show login form, no message
    	$header = "Einloggen";
    	$message = "";
    	$form = $loginForm;
    	return new ViewModel(
    			array(
    					'widgetHeader' 		=> $header,
    					'form' 				=> $form,
    					'message'			=> $message
    			)
    	);
    }
    
    public function testAction(){
    	// create action controller, testAction()
    	// create route in module.config.php
    	// return Model for corresponding view, test.phtml
    	$header = "IndexController.testAction()";
    	$content = "";
    	$content .= "<h2>Webclient</h2>";
    	
    	// ZendLog
//     	$logFile = APP_ROOT . '/data/logs/application.log';
//     	$formatter = new \Zend\Log\Formatter\Simple('%timestamp% - %priorityName% (%priority%), %message% %extra%', 'Y-m-d H:i:s');
//     	$writer = new \Zend\Log\Writer\Stream($logFile);
//     	$writer->setFormatter($formatter);
//     	$logger = new \Zend\Log\Logger();
//     	$logger->addWriter($writer);
//     	$testString = "Mein TestString!";
//     	$logger->log(\Zend\Log\Logger::INFO, $testString);
    	
    	// Zend Session 
//     	$userSession = new Container('user'); // use Zend\Session\Container;
//     	$userName = $userSession->userName;
//     	if ($userName == ""){
//     		return $this->redirect()->toRoute('login');
//     	}
    	
    	// Db-Interaktion ohne Hydrator ohne Entity ohne ModelService;
//     	$db = $this->getServiceLocator()->get('db');
//     	$query = "SELECT * FROM widget WHERE name LIKE 'home';";
//     	$res = $db->query(
//     			$query, Adapter::QUERY_MODE_EXECUTE
//     	);
//     	foreach($res as $row){
//     		$header = $row['header'];
//     		$content = $row['content'];
//     	}
    	
    	// Akteure 
    	// User [Systemnutzer, Admin, Dienstleister]
    	// Person - (abstrakt)[Bewohner, Gast]
    	// Raum - (abstrakt) [Wohnzimmer, Schlafzimmer, Kueche]

    	// TableGateway Pattern isoliert
//     	$db = $this->getServiceLocator()->get('db');
//     	$res = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new UserEntity());
//     	$userTable = new TableGateway('user', $db);
//     	$rows = $userTable->select(array('username' => 'joba'));
//     	foreach($rows as $row){
//     		Debug::dump($row->getArrayCopy());
//     	}
    	
    	// Modelservice 
    	// Idee: Controller haelt Modelservice-Objekt, dieses wiederum das TableObjekt;
    	// $tempService = $this->getUserService();
//     	$db = $this->getServiceLocator()->get('db');
//     	$userTable = new TableGateway('user', $db);
//     	$tempService = new UserService($userTable);
//     	$rows = $tempService->fetchList();
//     	//$rows = $tempService->fetchSingleById(array('id', 1));
//     	foreach($rows as $row){
//     		Debug::dump($row->getArrayCopy());
//     		$content .= "<p>";
//     		$content .= $row['username'];
//     		$content .= "</p>";
//     	}
    	
    	// DbInteraktion mit TableGateway
    	// DbModel
    	// user: id, username, password, role;
    	// widget: id, name, header, content, userid;
    	// DbAdapter konfigurieren, siehe global.php
    	// entity Klasse anlegen;
    	
    	// Konstanten via define() in index.php anlegen, hier APP_ROOT 
    	
    	// ACL Test
    	// Aktuelle Rolle wird in session gespeichert, diese Variable landet in isAllowed()-Methode;
    	// Eine resource ist am besten stets eine vorher angelegte route;
    	// Die Acl wird am besten in der Modul-onBootstrap() angelegt und oder gecacht;
//     	$acl = new Acl(); // TODO cachen nach Erstellung;
//     	$acl->addRole('guest');
//     	$acl->addRole('admin');
//     	$acl->addResource('product');
//     	$acl->addResource('order');
//     	//$acl->allow('guest', 'product', array('show', 'list'));
//     	$acl->allow('admin');
//     	$acl->deny('guest', 'order');
//     	Debug::dump($acl->getRoles());
//     	Debug::dump($acl->getResources());
//     	$userSession->userRole = "guest";
//     	$userRole = $userSession->userRole;
//     	Debug::dump($acl->isAllowed($userRole, 'product', 'show'));
    	
    	// object room und resident modellieren
    	// room - properties: id name humidity;
    	// resident - properties: id name; relations: room;
    	// RoomEntity.php inkl. exchangeArray getArrayCopy anlegen;
    	// ResidentEntity.php inkl. exchangeArray getArrayCopy anlegen;
    	// ggf. ResidentEntityInterface und RoomEntityInterface;
    	// Datenbanktabellen anlegen, Fremdschluessel fuer unidirektionale 1:1 Beziehung unterbringen;
    	// Model-Service-Klassen unterbringen;
    	// Ggf. factory-Methoden anlegen;
    	// Ggf. constructor injection umsetzen;
    	// Formularklassen fuer den jewweiligen Modelservice erzeugen;
    	// Zugehoerige Filterklassen anlegen;
    	// Zugehoerige Views anlegen (Formklassen legen action-Attribut fest, ControllerAction und zugehöriger View muss da sein;
    	// Aktuell ist alles auf testAction des IndexControllers geschalten;
    	// Services in ...
    	
    	//$tempResidentService = new ResidentService();
    	$db = $this->getServiceLocator()->get('db');
//     	$resultSetPrototypeRoom = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new RoomEntity());
//     	$roomTable = new TableGateway('room', $db, null, $resultSetPrototypeRoom);
     	$roomTable = new TableGateway('room', $db); 
    	//$resultSetPrototypeResident = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new ResidentEntity());
    	//$residentTable = new TableGateway('resident', $db, null, $resultSetPrototypeResident);
    	$residentTable = new TableGateway('resident', $db);
    	$tempRoomService = new RoomService();
    	$tempResidentService = new ResidentService();
    	$tempRoomService->setTable($roomTable);
    	$tempResidentService->setTable($residentTable);
    	$this->setRoomService($tempRoomService);
    	$this->setResidentService($tempResidentService);
    	
    	// CRUD 
    	//Debug::dump($this->getRoomService()->fetchList());
    	//Debug::dump($this->getResidentService()->fetchList());
    	
    	// Read All
//     	$content .= "<h2>Residents</h2>";
//     	$residents = $this->getResidentService()->fetchList();
//     	foreach($residents as $resident){
//     		$content .= "<p>";
//     		$content .= $resident->id;
//     		$content .= "; ";
//     		$content .= $resident->name;
//     		$content .= "</p>";
//     	}
//     	$content .= "<h2>Rooms</h2>";
//     	$rooms = $this->getRoomService()->fetchList();
//     	foreach($rooms as $room){
//     		$content .= "<p>";
//     		$content .= $room->id;
//     		$content .= "; ";
//     		$content .= $room->name;
//     		$content .= "</p>";
//     	}
    	
    	// Read One
//     	$content .= "<h2>Resident By Id</h2>";
//     	$resident = $this->getResidentService()->fetchSingleById(1);
//     	Debug::dump($resident);
//     	$content .= "<p>";
//     	$content .= $resident;
//     	$content .= "</p>";
//     	$content .= "<h2>Room By Id</h2>";
//     	$room = $this->getRoomService()->fetchSingleById(2);
//     	Debug::dump($room);
//     	$content .= "<p>";
//     	$content .= $room;
//     	$content .= "</p>";

    	// Update
    	
    	return new ViewModel(array(
        		'header' => $header,
        		'content' => $content
    	));
    }
    
}
