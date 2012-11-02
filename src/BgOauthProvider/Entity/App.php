<?php

namespace BgOauthProvider\Entity;

use Doctrine\ORM\Mapping as ORM;

class App implements AppInterface
{

    private $id;

    private $consumer_key;

    private $consumer_secret;

    private $app_name;

    private $description;

    private $base_url;

    private $app_hash;

    private $status;

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
     * Set consumer_key
     *
     * @param string $consumerKey
     * @return App
     */
    public function setConsumerKey($consumerKey)
    {
        $this->consumer_key = $consumerKey;
        return $this;
    }

    /**
     * Get consumer_key
     *
     * @return string
     */
    public function getConsumerKey()
    {
        return $this->consumer_key;
    }

    /**
     * Set consumer_secret
     *
     * @param string $consumerSecret
     * @return App
     */
    public function setConsumerSecret($consumerSecret)
    {
        $this->consumer_secret = $consumerSecret;
        return $this;
    }

    /**
     * Get consumer_secret
     *
     * @return string
     */
    public function getConsumerSecret()
    {
        return $this->consumer_secret;
    }

    /**
     * Set app_name
     *
     * @param string $appName
     * @return App
     */
    public function setAppName($appName)
    {
        $this->app_name = $appName;
        return $this;
    }

    /**
     * Get app_name
     *
     * @return string
     */
    public function getAppName()
    {
        return $this->app_name;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return App
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set base_url
     *
     * @param string $baseUrl
     * @return App
     */
    public function setBaseUrl($baseUrl)
    {
        $this->base_url = $baseUrl;
        return $this;
    }

    /**
     * Get base_url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * Set app_hash
     *
     * @param string $appHash
     * @return App
     */
    public function setAppHash($appHash)
    {
        $this->app_hash = $appHash;
        return $this;
    }

    /**
     * Get app_hash
     *
     * @return string
     */
    public function getAppHash()
    {
        return $this->app_hash;
    }

    /**
     * Set status
     *
     * @param smallint $status
     * @return App
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return smallint
     */
    public function getStatus()
    {
        return $this->status;
    }
}