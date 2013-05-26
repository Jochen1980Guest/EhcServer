<?php
namespace Application\Entity;

use Zend\Stdlib\ArraySerializableInterface;

class RoomEntity implements ArraySerializableInterface
{
    protected $id;
    protected $name;
    protected $humidity;
    
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
    
    public function setHumidity($humidity){
    	$this->humidity = $humidity;
    }
    
    public function getHumidity(){
    	return $this->humidity;
    }
    
    public function exchangeArray(array $array){
        $this->setId($array['id']);
        $this->setName($array['name']);
        $this->setName($array['humidity']);
    }
    
    public function getArrayCopy(){
        return array(
            'id'    => $this->getId(),
            'name'  => $this->getName(),
        	'humidity'  => $this->getHumidity(),
        );
    }
    
    public function __toString(){
    	$str = "";
    	$str .= "RoomEntity - ";
    	$str .= "ID : " . $this->getId() . "; ";
    	$str .= "NAME : " .  $this->getName() . "; ";
    	$str .= "HUMIDITY : " . $this->getHumidity() . "; ";
    	return $str;
    }
}