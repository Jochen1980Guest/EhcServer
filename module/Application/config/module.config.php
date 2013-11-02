<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
        	'rest' => array(
        		'type' => 'Segment',
        		'options' => array(
        			'route' => '/rest[/:id]',
        			'constraints' => array(
        				'id' => '[0-9]+',		
        			),
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'Application\Controller\Rest',
					),
        		),
        	),
        	'clearlog' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/utilities/clearLog',
        			'defaults' => array(
        				'controller' => 'Application\Controller\Utilities',
        					'action'     => 'clearLog',
        				),
        		),
        	),
        	'documentation' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/utilities/showDocumentation',
        				'defaults' => array(
        					'controller' => 'Application\Controller\Utilities',
        						'action'     => 'showDocumentation',
        				),
        		),
        	),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'login' => array(
            		'type' => 'Zend\Mvc\Router\Http\Literal',
            		'options' => array(
            				'route'    => '/index/login',
            				'defaults' => array(
            						'controller' => 'Application\Controller\Index',
            						'action'     => 'login',
            				),
            		),
            ),
            'temp' => array(
            		'type' => 'Zend\Mvc\Router\Http\Literal',
            		'options' => array(
            				'route'    => '/index/temp',
            				'defaults' => array(
            						'controller' => 'Application\Controller\Index',
            						'action'     => 'temp',
            				),
            		),
            ),
            'test' => array(
            		'type' => 'Zend\Mvc\Router\Http\Literal',
            		'options' => array(
            				'route'    => '/utilities/test',
            				'defaults' => array(
            						'controller' => 'Application\Controller\Utilities',
            						'action'     => 'test',
            				),
            		),
            ),
            'utilities' => array(
            		'type' => 'Zend\Mvc\Router\Http\Literal',
            		'options' => array(
            				'route'    => '/utilities/index',
            				'defaults' => array(
            						'controller' => 'Application\Controller\Utilities',
            						'action'     => 'index',
            				),
            		),
            ),
            'showroom' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route'    => '/:id',
            				'defaults' => array(
            						'controller' => 'ZfcUser\Controller\User',
            						'action'     => 'showRoom',
            						'id'		 => '1',
            				),
            		),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
//             'application' => array(
//                 'type'    => 'Literal',
//                 'options' => array(
//                     'route'    => '/application',
//                     'defaults' => array(
//                         '__NAMESPACE__' => 'Application\Controller',
//                         'controller'    => 'Index',
//                         'action'        => 'index',
//                     ),
//                 ),
//                 'may_terminate' => true,
//                 'child_routes' => array(
//                     'default' => array(
//                         'type'    => 'Segment',
//                         'options' => array(
//                             'route'    => '/[:controller[/:action]]',
//                             'constraints' => array(
//                                 'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                 'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
//                             ),
//                             'defaults' => array(
//                             ),
//                         ),
//                     ),
//                 ),
//             ),
        ),
    ),
    'controllers' => array(
    		'invokables' => array(
    				'Application\Controller\Index' 		=> 'Application\Controller\IndexController',
    				'Application\Controller\Rest' 		=> 'Application\Controller\RestController',
    				'Application\Controller\Utilities' 	=> 'Application\Controller\UtilitiesController',
    				'ZfcUser\Controller\User'			=> 'ZfcUser\Controller\UserController'
    		),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Application\Service\User'  => 'Application\Service\UserServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
        		'ViewJsonStrategy',
        ),
    ),
);
