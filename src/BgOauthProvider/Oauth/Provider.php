<?php

namespace BgOauthProvider\Oauth;

use BgOauthProvider\Entity\Token;
use \BgOauthProvider\Service\OauthInterface;
use \BgOauthProvider\Service\AppInterface;
use \BgOauthProvider\Entity\AppNonce;
use \BgOauthProvider\Entity\TokenInterface;
use \BgOauthProvider\Entity\AppInterface as AppEntityInterface;
use \OAuthProvider;

/**
 * OAuth 1.0a provider service
 * Uses pecl oauth @link http://pecl.php.net/package/oauth
 *
 * @author Brian Gallagher
 */
class Provider
{

    /**
     * @var \BgOauthProvider\Entity\App;
     */
    public $app;
    public $user;
    public $token;

    protected $oauthService;
    protected $appService;
    protected $provider;

    /**
     * @param \BgOauthProvider\Service\OauthInterface $oauthService
     * @param \BgOauthProvider\Service\AppInterface $appService
     * @param \BgOauthProvider\Entity\TokenInterface $token
     */
    public function __construct(OauthInterface $oauthService, AppInterface $appService)
    {
        $this->setOauthService($oauthService);
        $this->setAppService($appService);
        $this->token = new Token();
    }

    private function _getProvider()
    {
        if (null === $this->provider) {
            $this->provider = new OAuthProvider(array());
            $this->provider->consumerHandler(array($this, '_consumerHandler'));
            $this->provider->timestampNonceHandler(array($this, '_timestampNonceHandler'));
            $this->provider->tokenHandler(array($this, '_tokenHandler'));
        }

        return $this->provider;
    }

    /**
     * Proxy method to OAuthProvider::checkOAuthRequest
     *
     * @link http://www.php.net/manual/en/oauthprovider.checkoauthrequest.php
     */
    public function checkOAuthRequest()
    {
        $this->_getProvider()->checkOAuthRequest();
    }

    /**
     * Proxy method to OAuthProvider::isRequestTokenEndpoint
     * Also set 'oauth_callback' as a required parameter - per OAuth 1.0a
     *
     * @link http://www.php.net/manual/en/oauthprovider.setrequesttokenpath.php
     */
    public function setRequestTokenQuery()
    {
        $this->_getProvider()->isRequestTokenEndpoint(true);
        $this->_getProvider()->addRequiredParameter("oauth_callback");
    }

    /**
     * Proxy method to OAuthProvider::is2LeggedEndpoint
     *
     * @link http://www.php.net/manual/en/oauthprovider.is2leggedendpoint.php
     */
    public function is2LeggedEndpoint()
    {
        $this->_getProvider()->is2LeggedEndpoint(true);
    }

    /**
     * Implementation of the consumerHandler handler callback
     *
     * @link http://www.php.net/manual/en/oauthprovider.consumerhandler.php
     *
     * @param OAuthProvider $provider
     * @return string
     */
    public function _consumerHandler(OAuthProvider $provider)
    {
        $appService = $this->appService;

        if (null === $this->app) {
            $this->app = $appService->findAppByConsumerKey($provider->consumer_key);
        }

        if (!($this->app instanceof AppEntityInterface)) {
            return OAUTH_CONSUMER_KEY_UNKNOWN;
        }

        if ($this->app->getStatus() != AppEntityInterface::APP_ACTIVE) {
            return OAUTH_CONSUMER_KEY_REFUSED;
        }

        $provider->consumer_secret = $this->app->getConsumerSecret();

        return OAUTH_OK;
    }

    /**
     * Implementation of the timestampNonceHandler handler callback
     *
     * @link http://www.php.net/manual/en/oauthprovider.timestampnoncehandler.php
     *
     * @param OAuthProvider $provider
     * @return type Error Code
     */
    public function _timestampNonceHandler(OAuthProvider $provider)
    {

        $oauthService = $this->oauthService;

        if ($this->_getProvider()->timestamp < time() - 5 * 60) {
            return OAUTH_BAD_TIMESTAMP;
        }

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($this->_getProvider()->timestamp);

        $oldNonce = $oauthService->findNonce($this->app->getId(), $provider->nonce, $dateTime);

        if ($oldNonce) {
            return OAUTH_BAD_NONCE;
        }

        $appNonce = new AppNonce();
        $appNonce->setNonce($provider->nonce);
        $appNonce->setApp($this->app);
        $appNonce->setTimestamp($dateTime);

        $oauthService->saveAppNonce($appNonce);

        return OAUTH_OK;
    }

    /**
     * Implementation of the tokenHandler handler callback
     *
     * @link http://www.php.net/manual/en/oauthprovider.tokenhandler.php
     *
     * @param OAuthProvider $provider
     * @return type Error Code
     */
    public function _tokenHandler(OAuthProvider $provider)
    {
        $oauthService = $this->oauthService;

        $this->token = $oauthService->findToken($provider->token);

        if (!($this->token instanceof TokenInterface)) {
            return OAUTH_TOKEN_REJECTED;
        } elseif ($this->token->getType() == TokenInterface::TOKEN_REQUEST && $this->token->getVerifier() != $provider->verifier) {
            return OAUTH_VERIFIER_INVALID;
        } elseif ($this->app->getId() !== $this->token->getApp()->getId()) {
            return OAUTH_TOKEN_REJECTED;
        } else {

            $this->user = $this->token->getUser();

            $provider->token_secret = $this->token->getTokenSecret();

            return OAUTH_OK;
        }
    }

    /**
     * Proxy to \OAuthProvider::generateToken
     *
     * @param int $length
     * @return string
     */
    public function generateRandomHash($length = 20)
    {
        return sha1(OAuthProvider::generateToken($length));
    }

    /**
     * Populates and saves the constructor injected Token instance.
     *
     * @return \BgOauthProvider\Entity\TokenInterface
     */
    public function saveRequestToken()
    {
        $this->token->setType(TokenInterface::TOKEN_REQUEST);
        $this->token->setApp($this->app);
        $this->token->setToken($this->generateRandomHash());
        $this->token->setTokenSecret($this->generateRandomHash());
        $this->token->setCallbackUrl($this->provider->callback);

        $this->oauthService->saveToken($this->token);

        return $this->token;
    }

    /**
     * Modifies and saves the token found by _tokenHandler
     *
     * @return \BgOauthProvider\Entity\TokenInterface
     */
    public function saveAccessToken()
    {
        $this->token->setType(TokenInterface::TOKEN_ACCESS);
        $this->token->setToken($this->generateRandomHash());
        $this->token->setTokenSecret($this->generateRandomHash());

        $this->oauthService->updateToken($this->token);

        return $this->token;
    }

    public function setOauthService(OauthInterface $oauthService)
    {
        $this->oauthService = $oauthService;
    }

    public function setAppService(AppInterface $appService)
    {
        $this->appService = $appService;
    }

}