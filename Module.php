<?php

namespace BgOauthProvider;


use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

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
                'BgOauthProvider\Options' => function (ServiceLocatorInterface $serviceLocator) {
                    $config = $serviceLocator->get('Config');
                    return new Options\ModuleOptions(isset($config['bgoauthprovider']) ? $config['bgoauthprovider'] : array());
                },
                'BgOauthProvider\Mapper\AppNonce' => function (ServiceLocatorInterface $serviceLocator) {
                    return new \BgOauthProvider\Mapper\Doctrine\AppNonce(
                        $serviceLocator->get('doctrine.entitymanager.orm_default')
                    );
                },
                'BgOauthProvider\Mapper\Token' => function (ServiceLocatorInterface $serviceLocator) {
                    return new \BgOauthProvider\Mapper\Doctrine\Token(
                        $serviceLocator->get('doctrine.entitymanager.orm_default')
                    );
                },
                'BgOauthProvider\Mapper\App' => function (ServiceLocatorInterface $serviceLocator) {
                    return new \BgOauthProvider\Mapper\Doctrine\App(
                        $serviceLocator->get('doctrine.entitymanager.orm_default')
                    );
                },
                'BgOauthProvider\OauthService' => function (ServiceLocatorInterface $serviceLocator) {
                    return new \BgOauthProvider\Service\Oauth(
                        $serviceLocator->get('BgOauthProvider\Mapper\AppNonce'),
                        $serviceLocator->get('BgOauthProvider\Mapper\Token'),
                        $serviceLocator->get('BgOauthProvider\Mapper\App')
                    );
                },
                'BgOauthProvider\OauthProvider' => function (ServiceLocatorInterface $serviceLocator) {
                    return new \BgOauthProvider\Oauth\Provider(
                        $serviceLocator->get('BgOauthProvider\OauthService')
                    );
                },
                'BgOauthProvider\Acl' => function (ServiceLocatorInterface $serviceLocator) {
                    $aclConfig = $serviceLocator->get('BgOauthProvider\Options')->getAclConfig();
                    return new \BgOauthProvider\Acl($aclConfig);
                },
                'BgOauthProvider\RouteGuard' => function (ServiceLocatorInterface $serviceLocator) {
                    return new \BgOauthProvider\Guard\Route(
                        $serviceLocator->get('BgOauthProvider\Acl'),
                        $serviceLocator->get('BgOauthProvider\OauthProvider')
                    );
                },
            )
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'BgOauthProvider\Controller\Oauth' => function (ControllerManager $controllerManager) {
                    $serviceManager = $controllerManager->getServiceLocator();

                    return new \BgOauthProvider\Controller\OauthController(
                        $serviceManager->get('BgOauthProvider\OauthService'),
                        $serviceManager->get('BgOauthProvider\OauthProvider'),
                        $serviceManager->get('BgOauthProvider\Options')
                    );
                }
            ),
        );
    }
}
