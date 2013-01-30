<?php

namespace BgOauthProvider;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;


class Module implements AutoloaderProviderInterface, ServiceProviderInterface
{

    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $events = $app->getEventManager();
        $sm = $app->getServiceManager();

        $events->attach($sm->get('BgOauthProvider\RouteGuard'));
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
                'BgOauthProvider\Options' => function (ServiceManager $serviceLocator) {
                    $config = $serviceLocator->get('Config');
                    return new Options\ModuleOptions(isset($config['bgoauthprovider']) ? $config['bgoauthprovider'] : array());
                },
                'BgOauthProvider\OauthService' => function (ServiceManager $serviceLocator) {
                    return new \BgOauthProvider\Service\Oauth(
                        $serviceLocator->get('doctrine.entitymanager.orm_default')
                    );
                },
                'BgOauthProvider\AppService' => function (ServiceManager $serviceLocator) {
                    return new \BgOauthProvider\Service\App(
                        $serviceLocator->get('doctrine.entitymanager.orm_default')
                    );
                },
                'BgOauthProvider\OauthProvider' => function (ServiceManager $serviceLocator) {
                    return new \BgOauthProvider\Oauth\Provider(
                        $serviceLocator->get('BgOauthProvider\OauthService'),
                        $serviceLocator->get('BgOauthProvider\AppService'),
                        $serviceLocator->get('BgOauthProvider\TokenEntity')
                    );
                },
                'BgOauthProvider\Acl' => function (ServiceManager $serviceLocator) {

                    $aclConfig = $serviceLocator->get('BgOauthProvider\Options')->getAclConfig();

                    return new \BgOauthProvider\Acl($aclConfig);
                },
                'BgOauthProvider\RouteGuard' => function (ServiceManager $serviceLocator) {
                    return new \BgOauthProvider\Guard\Route(
                        $serviceLocator->get('BgOauthProvider\Acl'),
                        $serviceLocator->get('BgOauthProvider\OauthProvider')
                    );
                },
            ),
            'invokables' => array(
                'BgOauthProvider\TokenEntity' => 'BgOauthProvider\Entity\Token',
            ),
            'shared' => array(
                'BgOauthProvider\TokenEntity' => false,
            ),
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'BgOauthProvider\Controller\Oauth' => function(ControllerManager $controllerManager) {
                    $serviceManager = $controllerManager->getServiceLocator();

                    $controller = new \BgOauthProvider\Controller\OauthController();
                    $controller->setOauthService($serviceManager->get('BgOauthProvider\OauthService'));
                    $controller->setOauthProvider($serviceManager->get('BgOauthProvider\OauthProvider'));
                    $controller->setOptions($serviceManager->get('BgOauthProvider\Options'));

                    return $controller;
                }
            ),
        );
    }
}
