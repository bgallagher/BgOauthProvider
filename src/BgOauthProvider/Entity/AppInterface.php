<?php

namespace BgOauthProvider\Entity;

interface AppInterface
{
    const APP_ACTIVE = 1;
    const APP_INACTIVE = 0;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set consumer_key
     *
     * @param string $consumerKey
     * @return App
     */
    public function setConsumerKey($consumerKey);

    /**
     * Get consumer_key
     *
     * @return string
     */
    public function getConsumerKey();

    /**
     * Set consumer_secret
     *
     * @param string $consumerSecret
     * @return App
     */
    public function setConsumerSecret($consumerSecret);

    /**
     * Get consumer_secret
     *
     * @return string
     */
    public function getConsumerSecret();

    /**
     * Set app_name
     *
     * @param string $appName
     * @return App
     */
    public function setName($appName);

    /**
     * Get app_name
     *
     * @return string
     */
    public function getName();

    /**
     * Set description
     *
     * @param text $description
     * @return App
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription();

    /**
     * Set base_url
     *
     * @param string $baseUrl
     * @return App
     */
    public function setUrl($baseUrl);

    /**
     * Get base_url
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set status
     *
     * @param smallint $status
     * @return App
     */
    public function setStatus($status);

    /**
     * Get status
     *
     * @return smallint
     */
    public function getStatus();
}
