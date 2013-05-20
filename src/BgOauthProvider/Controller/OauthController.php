<?php

namespace BgOauthProvider\Controller;

use BgOauthProvider\Oauth\Provider as OauthProvider;
use BgOauthProvider\Options\ModuleOptions;
use BgOauthProvider\Service\Oauth as OauthService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OauthController extends AbstractActionController
{

    /**
     * @var OauthProvider
     */
    protected $oauthProvider;

    /**
     * @var OauthService
     */
    protected $oauthService;

    /**
     * @var ModuleOptions;
     */
    protected $options;

    protected $failedLoginMessage = 'Authentication failed. Please try again.';

    public function __construct(OauthService $oauthService, OauthProvider $oauthProvider, ModuleOptions $options)
    {
        $this->oauthService = $oauthService;
        $this->oauthProvider = $oauthProvider;
        $this->options = $options;
    }

    public function requestTokenAction()
    {
        $oauthProvider = $this->oauthProvider;
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
            $response->getHeaders()->addHeaders(
                array(
                    'WWW-Authenticate' => $error,
                )
            );

        }

        return $response;
    }

    public function authorizeAction()
    {
        $oauthService = $this->oauthService;
        $oauthProvider = $this->oauthProvider;

        $request = $this->getRequest();

        $oauth_token = $request->getQuery()->get('oauth_token');

        $token = $oauthService->findToken($oauth_token);

        if (!$token) {
            return $this->getResponse()->setContent('oauth_token not found');
        }

        $hasIdentity = $this->zfcUserAuthentication()->getAuthService()->hasIdentity();

        $app = $token->getApp();

        /**
         * Retrieve zfcuser login form, and
         * add a deny buton, and change submit to allow
         */
        $form = $this->getServiceLocator()->get('zfcuser_login_form');
        $form->add(
            array(
                'name' => 'deny',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'true',
                    'id' => 'deny-button',
                ),
                'options' => array(
                    'label' => 'deny',
                )
            )
        );

        if ($hasIdentity) {
            $form->get("submit")->setLabel('allow');
            $form->remove('identity');
            $form->remove('credential');
        } else {
            $form->get("submit")->setLabel('login and allow');
        }

        $fm = $this->flashMessenger()->setNamespace('bgoauthprovider-authorisation-form')->getMessages();
        if (isset($fm[0])) {
            $form->setMessages(
                array('identity' => array($fm[0]))
            );
        }

        /**
         * Attempt login.
         */
        if ($request->isPost() && !$hasIdentity) {

            if ($request->getPost()->get('deny')) {
                die('denied');
            }

            $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
            $adapter->prepareForAuthentication($request);
            $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

            /**
             * Redirect back if login fails.
             */
            if (!$auth->isValid()) {
                $this->flashMessenger()->setNamespace('bgoauthprovider-authorisation-form')->addMessage(
                    $this->failedLoginMessage
                );
                $adapter->resetAdapters();
                return $this->redirect()->toUrl(
                    $this->url()->fromRoute('bgoauthprovider/v1/authorize')
                    . '?' . http_build_query(array('oauth_token' => $oauth_token))
                );
            } else {
                $hasIdentity = true;
            }
        }

        /**
         * Processing OAuth authorisation request...
         */
        if ($hasIdentity) {

            $user = $this->zfcUserAuthentication()->getIdentity();

            if ($request->isPost() || $this->oauthService->isRepeatAuthorization($user, $app)) {

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

        }

        //Falling through to the view.

        $viewModel = new ViewModel(array(
            'authenticationForm' => $form,
            'app' => $app,
            'selfUrl' => $this->url()->fromRoute(
                'bgoauthprovider/v1/authorize'
            )
            . '?' . http_build_query($this->params()->fromQuery()),
        ));

        $viewModel->setTerminal(
            $this->options->getDisableLayoutOnAuthorisationPage()
        );

        return $viewModel;
    }

    public function accessTokenAction()
    {
        $oauthProvider = $this->oauthProvider;
        $response = $this->getResponse();

        try {
            $oauthProvider->checkOAuthRequest();
            $accessToken = $oauthProvider->saveAccessToken();

            $responseUrl = array();
            $responseUrl['oauth_token'] = $accessToken->getToken();
            $responseUrl['oauth_token_secret'] = $accessToken->getTokenSecret();

            $response->setContent(http_build_query($responseUrl));

        } catch (\OAuthException $e) {
            $error = \OAuthProvider::reportProblem($e);

            $response->setStatusCode(400);
            $response->setContent($error);
            $response->getHeaders()->addHeaders(
                array(
                    'WWW-Authenticate' => $error,
                )
            );

        }

        return $response;
    }

}