<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'api-user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/user[/:id]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                    ],
                ],
            ],
            'api-user-seed' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/user/seed',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'seed',
                    ],
                ],
            ],
            'api-user-export-xls' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/user/export-xls',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'exportXls',
                    ],
                ],
            ],
            'api-user-export-pdf' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/user/export-pdf',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'exportPdf',
                    ],
                ],
            ],
            'api-education' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/education[/:id]',
                    'defaults' => [
                        'controller' => Controller\EducationController::class,
                    ],
                ],
            ],
            'api-education-seed' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/education/seed',
                    'defaults' => [
                        'controller' => Controller\EducationController::class,
                        'action' => 'seed',
                    ],
                ],
            ],
            'docs' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/docs',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'docs',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\UserController::class => function($container) {
                return new Controller\UserController(
                    $container->get('doctrine.entitymanager.orm_default')
                );
            },
            Controller\EducationController::class => function($container) {
                return new Controller\EducationController(
                    $container->get('doctrine.entitymanager.orm_default')
                );
            },
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
