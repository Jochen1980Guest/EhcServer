<?php

namespace Application\Service;

use Zend\Db\Sql\Sql;

use Application\Entity\ResidentEntity;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\Debug;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Filter\StaticFilter;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;

class ResidentService {
    protected $table = null;
    protected $message = null;
    protected $form = null;
    
    public function __construct(){
    }
    
    public function getTable()
    {
        return $this->table;
    }
    
    public function setTable(TableGateway $table){
        $this->table = $table;
        return $this; // Check warum der setter hier etwas returniert
    }
    
    public function setForm(ResidentForm $form){
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
    	$db = $this->getTable()->getAdapter();
    	//Debug::dump($db);
    	$rows = $this->getTable()->select(array('id' => $id));
    	foreach($rows as $row){
    		$res = new ResidentEntity();
    		$res->setId($row['id']);
    		$res->setName($row['name']);
    		$res->setRoom($row['room']);
    	}
    	return $res;
        //return $this->getTable()->fetchSingleById($id);
    }
    
    public function fetchList($page = 1, $perPage = 15)
    {
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
        
        // return paginator
        return $paginator;
        //return $select;
    }
    
    public function save(array $data, $id = null)
    {
        // check mode
        $mode = is_null($id) ? 'create' : 'update';
                
        // get blog entity
        if ($mode == 'create') {
            $blog = new BlogEntity();
        } else {
            $blog = $this->fetchSingleById($id);
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
        $blog->exchangeArray($form->getData());
        
        // set values
        if ($mode == 'create') {
            $blog->setCdate(date('Y-m-d H:i:s'));
        }
        $blog->setUrl(StaticFilter::execute($blog->getTitle(), 'StringToUrl'));
        
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