<?php

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\Driver\OCI8\Driver as OCI8;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Import\Service\CallService;
use Zend\Mvc\Router\Console\Simple;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return array(
    'bjyauthorize'    => [
        'guards' => [
            \UnicaenAuth\Guard\PrivilegeController::class => [
                [
                    'controller' => Import\Controller\ImportController::class,
                    'action'     => [
                        'help',
                        'import',
                        'import-all',
                        'update-these',
                        'index',
                        'info-last-update',
                    ],
                    'roles' => [
                        'Administrateur technique',
                    ],
                ],
                [
                    'controller' => Import\Controller\ImportController::class,
                    'action'     => [
                        'import-console',
                        'import-all-console',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],
    'doctrine'     => [
        'driver'     => [
            'orm_default'        => [
                'class'   => MappingDriverChain::class,
                'drivers' => [
                    'Import\Model' => 'orm_default_xml_driver',
                ],
            ],
            'orm_default_xml_driver' => [
                'class' => XmlDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Import/Model/Mapping',
                ],
            ],
        ],
        'connection'    => [
            'orm_default' => [
                'driver_class' => OCI8::class,
            ],
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'import-console' => [
                    'type' => Simple::class,
                    'options' => [
                        'route'    => 'import --service=  --etablissement= [--source-code=]',
                        'defaults' => [
                            'controller' => Import\Controller\ImportController::class,
                            'action'     => 'import-console',
                        ],
                    ],
                ],
                'import-all-console' => [
                    'type' => Simple::class,
                    'options' => [
                        'route'    => 'import-all --etablissement=',
                        'defaults' => [
                            'controller' => Import\Controller\ImportController::class,
                            'action'     => 'import-all-console',
                        ],
                    ],
                ],
            ],
        ],
        'view_manager' => [
            'display_not_found_reason' => true,
            'display_exceptions'       => true,
        ]
    ],
    'router' => [
        'routes' => [
            'home-import' => [
                'type' => 'Literal',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/ws-import',
                    'defaults' => [
                        'controller' => Import\Controller\ImportController::class,
                        'action'     => 'index',
                    ],
                ],
                'child_routes' => [
                    'info-last-update' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/info-last-update/:table/:etablissement',
                            'defaults' => [
                                'controller' => Import\Controller\ImportController::class,
                                'action'     => 'info-last-update',
                            ],
                        ],
                    ],
                    'import' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/import/:service/:etablissement[/:source_code]',
                            'defaults' => [
                                'controller' => Import\Controller\ImportController::class,
                                'action'     => 'import',
                            ],
                            'constraints' => [
                                'service' => implode('|', [
                                    "these",
                                    "doctorant",
                                    "acteur",
                                    "variable",
                                    "individu",
                                    "role",
                                    "structure",
                                    "etablissement",
                                    "unite-recherche",
                                    "ecole-doctorale",
                                ]),
                            ]
                        ],
                    ],
                    'import-all' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/import-all/:etablissement',
                            'defaults' => [
                                'controller'    => Import\Controller\ImportController::class,
                                'action'        => 'import-all',
                                'etablissement' => 'non renseigné',
                            ],
                        ],
                    ],
                    'update-these' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/update-these/:etablissement[/:source_code]',
                            'defaults' => [
                                'controller' => Import\Controller\ImportController::class,
                                'action'     => 'update-these',
                            ],
                        ],
                    ],
                    'help' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/help',
                            'defaults' => [
                                'controller'    => Import\Controller\ImportController::class,
                                'action'        => 'help',
                            ],
                        ],
                    ]
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            CallService::class => CallService::class,
        ],
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'factories' => [
            Import\Service\ImportService::class  => Import\Service\Factory\ImportServiceFactory::class,
            Import\Service\FetcherService::class => Import\Service\Factory\FetcherServiceFactory::class,
            Import\Service\SynchroService::class => Import\Service\Factory\SynchroServiceFactory::class,
        ],
        'aliases' => [
            'ImportService' => Import\Service\ImportService::class,
        ]

    ],
    'controllers' => [
        'factories' => [
            Import\Controller\ImportController::class => Import\Controller\Factory\ImportControllerFactory::class,
        ],
    ],
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
