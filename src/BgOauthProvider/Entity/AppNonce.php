<?php

namespace BgOauthProvider\Entity;

use Doctrine\ORM\Mapping as ORM;

class AppNonce
{

    private $id;

    private $timestamp;

    private $nonce;

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
     * Set timestamp
     *
     * @param datetime $timestamp
     * @return AppNonce
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * Get timestamp
     *
     * @return datetime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set nonce
     *
     * @param string $nonce
     * @return AppNonce
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * Get nonce
     *
     * @return string
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * Set app
     *
     * @param App $app
     * @return AppNonce
     */
    public function setApp(App $app = null)
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