<?php

namespace BgOauthProvider;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;


class Module implements AutoloaderProviderInterface, ServiceProviderInterface
{

    public function onBootstrap($event)
    {
        $app = $event->getTarget();
        $events = $app->getEventManager();
        $sm = $app->getServiceManager();

        $events->attach($sm->get('BgOauthRouteGuard'));
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'BgOauthService' => function ($serviceLocator) {
                    return new \BgOauthProvider\Service\Oauth(
                        $serviceLocator->get('doctrine.entitymanager.orm_default')
                    );
                },
                'BgAppService' => function ($serviceLocator) {
                    return new \BgOauthProvider\Service\App(
                        $serviceLocator->get('doctrine.entitymanager.orm_default')
                    );
                },
                'BgOauthProvider' => function ($serviceLocator) {
                    return new \BgOauthProvider\Oauth\Provider(
                        $serviceLocator->get('BgOauthService'),
                        $serviceLocator->get('BgAppService'),
                        $serviceLocator->get('BgTokenEntityConcrete')
                    );
                },
                'BgOauthAcl' => function ($serviceLocator) {
                    $config = $serviceLocator->get('Configuration');
                    $aclConfig = array_key_exists('BgOauthAcl', $config) ? $config['BgOauthAcl'] : array();

                    return new \BgOauthProvider\Acl($aclConfig);
                },
                'BgOauthRouteGuard' => function ($serviceLocator) {
                    return new \BgOauthProvider\Guard\Route(
                        $serviceLocator->get('BgOauthAcl'),
                        $serviceLocator->get('BgOauthProvider')
                    );
                },
            ),
            'invokables' => array(
                'BgTokenEntityConcrete' => 'BgOauthProvider\Entity\Token',
            ),
            'shared' => array(
                'BgTokenEntityConcrete' => false,
            ),
        );
    }
}
