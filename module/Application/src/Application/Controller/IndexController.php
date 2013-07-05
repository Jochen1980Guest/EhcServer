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
use Zend\Http\Client;
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
        		'content' => $content,
        		'loginUrl' => $this->url('login')
        	) 
        );
    }

	public function historieAction(){
        return new ViewModel();
    }
    
    public function loginAction(){
		// Weiterleitung an ZfcUser
    	return $this->redirect()->toRoute('zfcuser');
    }
    
    public function tempAction(){
    	// create action controller, testAction()
    	// create route in module.config.php
    	// return Model for corresponding view, test.phtml
    	$header = "IndexController.tempAction()";
    	$content = "";
    	$content .= "<h2>Webclient</h2>";
    	// Alternativ Youtube API nutzen
    	//$urlString = "http://gdata.youtube.com/feeds/api/videos?orderby=published&alt=json&q=ios";
    	//$urlString = "http://ehcserver.localhost/rest/4";
    	$urlString = "http://ehcserver.localhost/rest";
    	$client = new Client($urlString);
    	$client->setMethod('get');
    	$response = $client->send();
    	$content .= "<pre>" . $response->getContent() . "</pre>";
    	
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
//     	$db = $this->getServiceLocator()->get('db');
// //     	$resultSetPrototypeRoom = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new RoomEntity());
// //     	$roomTable = new TableGateway('room', $db, null, $resultSetPrototypeRoom);
//      	$roomTable = new TableGateway('room', $db); 
//     	//$resultSetPrototypeResident = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, new ResidentEntity());
//     	//$residentTable = new TableGateway('resident', $db, null, $resultSetPrototypeResident);
//     	$residentTable = new TableGateway('resident', $db);
//     	$tempRoomService = new RoomService();
//     	$tempResidentService = new ResidentService();
//     	$tempRoomService->setTable($roomTable);
//     	$tempResidentService->setTable($residentTable);
//     	$this->setRoomService($tempRoomService);
//     	$this->setResidentService($tempResidentService);
    	
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
    	
    	// Einbau Benutzermanagement Eggert:
    	// Modul User anlegen, siehe https://github.com/ZF2Buch/kapitel17
    	// Ordner User (hier die Startvariante) in module unterbringen
    	// Scan des Moduls auf Quelltextebene:
    	// Einhaengen in application, also Root-config-application.config.php
    	// Check der user route;
    	// Pitfall: pageTitle-Helper ist nicht da. Es gibt einen korrespondierenden ViewHelper in Application ist da oder man unterbindet den Aufruf, hier die Variante; 
    	// TODO Check Vorteil ViewHelper PageTitle.php;
    	// UserController.indexAction() wird bei user-Route gerufen;
    	// Es wird direkt auf den View durchgefasst;
    	// TODO Check Funktionsweise UserController implementiert eigenes Interface und nutzt DI auf ServiceInjection;
    	// Sofern der php-Code auskommentiert ist, werden die Buttons angezeigt;
    	// Verwaltungslink in Navigation unterbringen via layout.phtml Anpassung;
    	// Hart umbiegen auf Controller-Level via return $this->redirect()->toRoute('user');
    	// Button User registrieren umsetzen: route anlegen; // Known Zf2Buch-Bug!
    	
    	// Einbau Benutzermanagement ZfcUser
    	// ZfcBase und dann ZfcUser holen und einhaengen;
    	// https://github.com/ZF-Commons/ZfcUser
    	// SignIn-View erscheint, siehe index.phtml und indexAction();
    	// Ziel ZfcUser-Modul unangetastet lassen.
    	// Ueberschreibe views via Konvention Application view zfc-user/user login.phtml und Application-Modul nach ZfcUser einbinden;
    	// Anpassen der Texte;
    	// TODO Anstreben internationaler Texte;
    	// Anstreben fehlerhafter Login, siehe Filter mit required;
    	// Authentication failed Fehlermeldung anpassen;
    	// Anstreben erfolgreicher Login: mysql schema aus data-Ordner nehmen;
    	// user: userid, username, email, display_name, password, state;
    	// Datenbankzugang ermoeglichen, siehe ServiceManager, out of the box;
    	// Ueberraschung, da wird dann direkt der Gravatar mit angezeigt;
    	// Speicherung erfolgt direkt verschluesselt in der Datenbank; 
    	// View nach erfolgreichem customizen;
    	
    	// -------------------------------------
    	// Rauswurf auf Login-Screen
//     	if ($this->zfcUserAuthentication()->hasIdentity()) {
//     		//get the email of the user
//     		$content .= "<p>Hallo!</p><p>Ihre Daten:<br />";
//     		$content .= $this->zfcUserAuthentication()->getIdentity()->getEmail();
//     		$content .= "<br />";
//     		//get the user_id of the user
//     		$content .= $this->zfcUserAuthentication()->getIdentity()->getId();
//     		$content .= "<br />";
//     		//get the username of the user
//     		$content .= $this->zfcUserAuthentication()->getIdentity()->getUsername();
//     		$content .= "<br />";
//     		//get the display name of the user
//     		$content .= $this->zfcUserAuthentication()->getIdentity()->getDisplayname();
//     		$content .= "</p>";
//     	} else {
//     		return $this->redirect()->toRoute('zfcuser');
//     	}

    	// Mail mit ZF2 verschicken
    	// http://www.michaelgallego.fr/blog/2012/07/19/how-to-use-zendmail/
    	// http://chalitstory.blogspot.de/2012/10/zend-framework-2-send-html-mail.html
    	
    	// Kontaktformular siehe PhlyContact
    	
    	return new ViewModel(array(
        		'header' => $header,
        		'content' => $content
    	));
    }
    
}
