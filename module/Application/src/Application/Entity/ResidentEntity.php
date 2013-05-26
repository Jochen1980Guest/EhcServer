<?php
namespace Application\Entity;

use Zend\Stdlib\ArraySerializableInterface;

class ResidentEntity implements ArraySerializableInterface
{
    protected $id;
    protected $name;
    protected $room; // Assoziation
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setRoom($room){
    	$this->room = $room;
    }
    
    public function getRoom(){
    	return $this->room;
    }
    
    public function exchangeArray(array $array){
        $this->setId($array['id']);
        $this->setName($array['name']);
    }
    
    public function getArrayCopy(){
        return array(
            'id'    => $this->getId(),
            'name'  => $this->getName(),
        );
    }
    
    public function __toString(){
    	$str = "";
    	$str .= "ResidentEntity - ";
    	$str .= "ID : " . $this->getId() . "; ";
    	$str .= "NAME : " .  $this->getName() . "; ";
    	$str .= "ROOM : " . $this->getRoom() . "; ";
    	return $str;
    }
}