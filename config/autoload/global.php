<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'service_manager' => array(
    	'factories' => array(
    		'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
    	),
    	'aliases' => array(
    		'db' => 'Zend\Db\Adapter\Adapter',
    	),
    ),
	'db' => array(
		'driver'    => 'pdo',
		'dsn'       => 'mysql:dbname=XXX;host=localhost',
		'username'  => 'XXX',
		'password'  => 'XXX',
		'driver_options' => array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
		),
	),
);
