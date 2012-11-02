<?php

namespace BgOauthProvider\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class OauthController extends AbstractActionController
{

    public function indexAction()
    {

        $result = new JsonModel(
            array(
                'action' => 'index',
            )
        );

        return $result;
    }

    public function requestTokenAction()
    {
        $oauthProvider = $this->getServiceLocator()->get('BgOauthProvider');
        $response = $this->getResponse();

        try {

            $oauthProvider->setRequestTokenQuery();
            $oauthProvider->checkOAuthRequest();

            $requestToken = $oauthProvider->saveRequestToken();

            $responseUrl = array();
            $responseUrl['oauth_token'] = $requestToken->getToken();
            $responseUrl['oauth_token_secret'] = $requestToken->getTokenSecret();

            $response->setContent(http_build_query($responseUrl));

        } catch (\OAuthException $e) {

            $error = \OAuthProvider::reportProblem($e);
            $response->setStatusCode(400);
            $response->setContent($error);
            $response->getHeaders()->addHeaders(array(
                'WWW-Authenticate' => $error,
            ));

        }

        return $response;
    }

    public function authorizeAction()
    {
        $oauthService = $this->getServiceLocator()->get('BgOauthService');
        $oauthProvider = $this->getServiceLocator()->get('BgOauthProvider');

        $request = $this->getRequest();

        $token = $oauthService->findToken($request->getQuery()->get('oauth_token'));

        if (!$token) {
            return $this->getResponse()->setContent('oauth_token not found');
        }

        $app = $token->getApp();

        /**
         * Retrieve zfcuser login form, and
         * add a deny buton, and change submit to allow
         */
        $form = $this->getServiceLocator()->get('zfcuser_login_form');
        $form->add(array(
            'name' => 'deny',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'true',
                'id' => 'deny-button',
            ),
            'options' => array(
                'label' => 'deny',
            )
        ));
        $form->get("submit")->setLabel("allow");


        if ($request->isPost()) {

            if ($request->getPost()->get('deny')) {
                die('denied');
            }

            if (!$this->zfcUserAuthentication()->getAuthService()->hasIdentity()) {

                $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
                $result = $adapter->prepareForAuthentication($request);

                $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

                if (!$auth->isValid()) {
                    $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
                    $adapter->resetAdapters();
                    return $this->redirect()->toUrl($this->url()->fromRoute('sfoauthprovider/authorize'));
                }

            }

            $user = $this->zfcUserAuthentication()->getIdentity();

            //do oauth stuff here
            $verifier = $oauthProvider->generateRandomHash();

            $token->setVerifier($verifier);
            $token->setUser($user);
            $oauthService->updateToken($token);

            $callback = $token->getCallbackUrl();

            $queryStringParams = array();
            $queryStringParams['oauth_token'] = $request->getQuery()->get('oauth_token');
            $queryStringParams['oauth_verifier'] = $token->getVerifier();
            $queryStringParams['oauth_callback_confirmed'] = "true";
            $queryString = http_build_query($queryStringParams);

            $parsedCallback = parse_url($callback);

            $callback .= (isset($parsedCallback['query']) ? '&' : '?') . $queryString;

            return $this->redirect()->toUrl($callback);

        }

        return array(
            'authenticationForm' => $form,
        );
    }

    public function accessTokenAction()
    {
        $oauthProvider = $this->getServiceLocator()->get('BgOauthProvider');
        $response = $this->getResponse();

        try {
            $oauthProvider->checkOAuthRequest();
            $requestToken = $oauthProvider->saveAccessToken();

            $responseUrl = array();
            $responseUrl['oauth_token'] = $requestToken->getToken();
            $responseUrl['oauth_token_secret'] = $requestToken->getTokenSecret();

            $response->setContent(http_build_query($responseUrl));

        } catch (\OAuthException $e) {
            $error = \OAuthProvider::reportProblem($e);

            $response->setStatusCode(400);
            $response->setContent($error);
            $response->getHeaders()->addHeaders(array(
                'WWW-Authenticate' => $error,
            ));

        }

        return $response;
    }

}