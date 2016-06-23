<?php

/**
 * Routes for the Timesheets Module
 * @author Sudhamsh Maddala <anjani.maddala@impelsys.com>
 */

namespace Timesheets;

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'timesheets' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/timesheets[/:action][/:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Timesheets\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            //1-> Dashboard
            'dashboard' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/timesheets/dashboard',
                    'defaults' => array(
                        'controller' => 'Timesheets\Controller\Index',
                        'action' => 'dashboard',
                    ),
                ),
            ),
            
            //2-> Time Sheet LISTING
            'currentmonth' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/timesheets/currentmonth',
                    'defaults' => array(
                        'controller' => 'Timesheets\Controller\Index',
                        'action' => 'currentmonth',
                    ),
                ),
            ),
            //3-> Archives
            'archives' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/timesheets/archives',
                    'defaults' => array(
                        'controller' => 'Timesheets\Controller\Index',
                        'action' => 'archives',
                    ),
                ),
            ),
            //4-> Specific Month's timesheet
             'previousmonth' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/timesheets/month[/:month_id]',
                    'constraints' => array(
                        'month_id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Timesheets\Controller\Index',
                        'action' => 'previousmonth',
                    ),
                ),
            ),

            //5-> Monthly/Yearly Statistics
             'statistics' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/timesheets/statistics',
                    'defaults' => array(
                        'controller' => 'Timesheets\Controller\Index',
                        'action' => 'statistics',
                    ),
                ),
            ),
            
            //2-> Shibboleth Authentication
            /*'shibbolethauth' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/shibboleth/authenticate',
                    'defaults' => array(
                        'controller' => 'Shibboleth\Controller\Index',
                        'action' => 'shibbolethauth',
                    ),
                ),
            ),
            
            //3-> Shibboleth Logout
            'shibbolethlogout' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/shibboleth/shibbolethlogout',
                    'defaults' => array(
                        'controller' => 'Shibboleth\Controller\Index',
                        'action' => 'shibbolethlogout',
                    ),
                ),
            ),
            
            */
            
           
        ),
    ),
    
    
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Timesheets\Controller\Index' => Controller\IndexController::class
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'default_template_suffix' => 'phtml',
    'layout' => 'application/layout',
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
