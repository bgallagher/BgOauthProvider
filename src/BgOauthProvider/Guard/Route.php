<?php

namespace BgOauthProvider\Guard;

use BgOauthProvider\Acl as BgOauthProviderAcl;
use BgOauthProvider\Oauth\Provider as BgOauthProvider;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class Route implements ListenerAggregateInterface
{

    protected $acl;
    protected $oauthProvider;
    protected $listener;

    public function __construct(BgOauthProviderAcl $acl, BgOAuthProvider $oauthProvider)
    {
        $this->acl = $acl;
        $this->oauthProvider = $oauthProvider;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listener = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -1000);
    }

    public function detach(EventManagerInterface $events)
    {
        $events->detach($this->listener);
    }

    public function onRoute(MvcEvent $mvcEvent)
    {
        $request = $mvcEvent->getRequest();
        $response = $mvcEvent->getResponse();

        $app = $mvcEvent->getTarget();
        $match = $app->getMvcEvent()->getRouteMatch();
        $routeName = $match->getMatchedRouteName();
        $method = strtolower($request->getMethod());

        if (!$this->acl->hasResource($routeName)) {
            return;
        }

        $role = null;

        /**
         * Infer that if :
         * 1. the request is protected under 2 legged oauth,
         *    then it is a 2 legged oauth request
         * 2. the request is protected under 3 legged oauth,
         *    then it is a 3 legged oauth request
         * 3. otherwise it is not protected
         */
        if ($this->acl->isAllowed(BgOauthProviderAcl::TWO_LEGGED, $routeName, $method)) {
            $role = BgOauthProviderAcl::TWO_LEGGED;
            $this->oauthProvider->is2LeggedEndpoint();
        } elseif ($this->acl->isAllowed(BgOauthProviderAcl::THREE_LEGGED, $routeName, $method)) {
            $role = BgOauthProviderAcl::THREE_LEGGED;
        } else {
            return;
        }

        try {
            $this->oauthProvider->checkOAuthRequest();
        } catch (\OAuthException $e) {

            $error = \OAuthProvider::reportProblem($e);
            $response->setStatusCode(400);
            $response->setContent($error);
            $response->getHeaders()->addHeaders(array(
                'WWW-Authenticate' => $error,
            ));

            return $response;
        }

        if (!$this->acl->isAllowed($role, $routeName, $method)) {

            $responseBody = array();
            $responseBody['error'] = 'Not Authorised';
            $responseBody['request'] = $request->getRequestUri();

            $response->setStatusCode(401);
            $response->setContent(json_encode($responseBody));
            $response->setHeaders($response->getHeaders()->addHeaderLine('Content-Type', 'application/json'));

            return $response;

        }

    }
}