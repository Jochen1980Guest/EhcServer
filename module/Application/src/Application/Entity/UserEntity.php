<?php
namespace Application\Entity;

use Zend\Stdlib\ArraySerializableInterface;

class UserEntity implements ArraySerializableInterface
{
    protected $id;
    protected $username;
    protected $password;
	protected $role;
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setUsername($username){
        $this->username = $username;
    }
    
    public function getUsername(){
        return $this->username;
    }
    
    public function setPassword($password){
        $this->password = $password;
    }
    
    public function getPassword(){
        return $this->password;
    }

	public function setRole($role){
        $this->role = $role;
    }
    
    public function getRole(){
        return $this->role;
    }
    
    public function exchangeArray(array $array){
        $this->setId($array['id']);
        $this->setUsername($array['username']);
        $this->setRole($array['role']);
    }
    
    public function getArrayCopy(){
        return array(
            'id'    => $this->getId(),
            'username'  => $this->getUsername(),
            'password' => $this->getPassword(),
			'role' => $this->getRole(),
        );
    }
}