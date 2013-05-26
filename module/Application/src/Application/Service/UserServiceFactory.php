<?php

namespace User\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class UserServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //$table   = $serviceLocator->get('Application\Table\User');
        //$auth    = $serviceLocator->get('User\Auth\Service');
        $db = $this->getServiceLocator()->get('db'); // TODO ggf. via module.config.php Objekte erzeugen
    	$table = new TableGateway('user', $db);
        $service = new UserService($table);
        return $service;
    }
}