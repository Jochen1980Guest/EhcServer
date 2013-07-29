<?php

namespace Application\Service;

use Application\Entity\RoomEntity;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\Debug;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Filter\StaticFilter;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class RoomService {
    protected $table = null;
    protected $message = null;
    protected $form = null; 
    
    public function __construct(){
    	// TODO use dependancy injection via constructor and create an interface as well;
    }
    
    public function getTable()
    {
        return $this->table;
    }
    
    public function setTable(TableGateway $table)
    {
        $this->table = $table;
        return $this;
    }
    
    public function setForm(RoomForm $form){
    	$this->form = $form;
    }
    
    public function getForm(){
    	return $this->form;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function clearMessage()
    {
        $this->message = null;
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function fetchSingleById($id)
    {
    	Debug::dump("RoomService.fetchSingleById()");
    	$db = $this->getTable()->getAdapter();
    	//Debug::dump($db);
    	$rows = $this->getTable()->select(array('id' => $id));
    	foreach($rows as $row){
    		$res = new RoomEntity();
    		$res->setId($row['id']);
    		$res->setName($row['name']);
    		$res->setHumidity($row['humidity']);
    	}
    	return $res;
        //return $this->getTable()->fetchSingleById($id);
    }
    
    public function fetchList($page = 1, $perPage = 15){
    	if ($page == "JSON"){ // TODO dirty!
    		//Debug::dump("RoomService.fetchList(JSON)");
    		return array( 
    			1 => "moep",
    			2 => "maap"
    		);
    	}	
    	//Debug::dump("RoomService.fetchList()");
    	// TODO vermutlich bekommt der Aufruf via Rest eine eigene Methode,
    	// oder der RestController baut das Objekt bzw. die Liste in ein Array um;
//     	return array( 
//     			1 => "moep",
//     			2 => "maap"
//     			);
        // Initialize select
    	$select = $this->getTable()->getSql()->select();
    	
        // Initialize paginator
        $adapter = new DbSelect(
            $select, 
            $this->getTable()->getAdapter(), 
            $this->getTable()->getResultSetPrototype()
        );
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($perPage);
        $paginator->setPageRange(9);
        return $paginator;
    }
    
    public function save(array $data, $id = null)
    {
        // check mode
        $mode = is_null($id) ? 'create' : 'update';
                
        // get blog entity
        if ($mode == 'create') {
            $room = new RoomEntity();
        } else {
            $room = $this->fetchSingleById($id);
        }
        
        // get form and set data
        $form = $this->getForm($mode);
        $form->setData($data);
        
        // check for invalid data
        if (!$form->isValid()) {
            $this->setMessage('Bitte Eingaben überprüfen!');
            return false;
        }
        
        // get valid blog entity object
        $room->exchangeArray($form->getData());
        
        // set values
//         if ($mode == 'create') { // optionale Attribute die bei neu zu kreierenden Objekten zu setzen sind;
//             $room->setCdate(date('Y-m-d H:i:s'));
//         }
        $room->setUrl(StaticFilter::execute($room->getTitle(), 'StringToUrl')); // passend anpassen und auf andere Attribute erweitern;
        
        // get insert data
        $saveData = $blog->getArrayCopy();
        
        // save blog
        try {
            if ($mode == 'create') {
                $this->getTable()->insert($saveData);
                
                // get last insert value
                $id = $this->getTable()->getLastInsertValue();
            } else {
                $this->getTable()->update($saveData, array('id' => $id));
            }
        } catch (InvalidQueryException $e) {
            $this->setMessage('Blogbeitrag wurde nicht gespeichert!');
            return false;
        }

        // reload blog
        $blog = $this->fetchSingleById($id);
        
        // set success message
        $this->setMessage('Blogbeitrag wurde gespeichert!');
        
        // return blog
        return $blog;
    }
    
    public function delete($id)
    {
        // fetch blog entity
        $blog = $this->fetchSingleById($id);
        
        // delete existing blog
        try {
            $result = $this->getTable()->delete(array('id' => $id));
        } catch (InvalidQueryException $e) {
            return false;
        }

        // set success message
        $this->setMessage('Der Blogbeitrag wurde gelöscht!');
        
        // return result
        return true;
    }
}