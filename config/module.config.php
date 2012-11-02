<?php

namespace BgOauthProvider;

return array(
    'controllers' => array(
        'invokables' => array(
            'BgOauthProvider\Controller\Oauth' => 'BgOauthProvider\Controller\OauthController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'sfoauthprovider' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/oauth/v1.0a',
                    'constraints' => array(),
                    'defaults' => array(
                        'controller' => 'BgOauthProvider\Controller\Oauth',
                        'action' => 'index',
                    ),
                ),
                'child_routes' => array(
                    'request_token' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/request_token',
                            'defaults' => array(
                                'controller' => 'BgOauthProvider\Controller\Oauth',
                                'action' => 'requestToken',
                            ),
                        ),
                    ),
                    'authorize' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authorize',
                            'defaults' => array(
                                'controller' => 'BgOauthProvider\Controller\Oauth',
                                'action' => 'authorize',
                            ),
                        ),
                    ),
                    'access_token' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/access_token',
                            'defaults' => array(
                                'controller' => 'BgOauthProvider\Controller\Oauth',
                                'action' => 'accessToken',
                            ),
                        ),
                    ),
                ),
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ZendSkeletonModule' => __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/xml/doctrineorm'
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);
