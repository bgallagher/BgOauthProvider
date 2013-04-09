<?php

namespace BgOauthProvider\Guard;

use BgOauthProvider\Acl as BgOauthProviderAcl;
use BgOauthProvider\Oauth\Provider as BgOauthProvider;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

class Route implements ListenerAggregateInterface
{

    protected $acl;
    protected $oauthProvider;
    protected $listener;

    const ERROR = 'error-unauthorized-oauth-request';

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

        /**
         * If it's a CLI request - return.
         */
        if($request instanceof \Zend\Console\Request)
        {
            return;
        }

        $app = $mvcEvent->getApplication();
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

            $error = \OAuthProvider::reportProblem($e, false);
            $response->setStatusCode(Response::STATUS_CODE_401);
            $response->setContent($error);
            $response->getHeaders()->addHeaders(
                array(
                    'WWW-Authenticate' => $error,
                )
            );

            $mvcEvent->setError(self::ERROR);
            $mvcEvent->getApplication()->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $mvcEvent);

            return $response;
        }

        //Success!
    }
}