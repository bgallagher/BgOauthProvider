<?php

namespace BgOauthProvider\Entity;

use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\User;

class Token implements TokenInterface
{
    private $id;

    private $type;

    private $token;

    private $token_secret;

    private $callback_url;

    private $verifier;

    private $user;

    private $app;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Token
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token_secret
     *
     * @param string $tokenSecret
     * @return Token
     */
    public function setTokenSecret($tokenSecret)
    {
        $this->token_secret = $tokenSecret;
        return $this;
    }

    /**
     * Get token_secret
     *
     * @return string
     */
    public function getTokenSecret()
    {
        return $this->token_secret;
    }

    /**
     * Set callback_url
     *
     * @param string $callbackUrl
     * @return Token
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callback_url = $callbackUrl;
        return $this;
    }

    /**
     * Get callback_url
     *
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callback_url;
    }

    /**
     * Set verifier
     *
     * @param string $verifier
     * @return Token
     */
    public function setVerifier($verifier)
    {
        $this->verifier = $verifier;
        return $this;
    }

    /**
     * Get verifier
     *
     * @return string
     */
    public function getVerifier()
    {
        return $this->verifier;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Token
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set app
     *
     * @param App $app
     * @return Token
     */
    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Get app
     *
     * @return App
     */
    public function getApp()
    {
        return $this->app;
    }
}