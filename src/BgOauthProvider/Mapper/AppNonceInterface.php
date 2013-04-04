<?php

namespace BgOauthProvider\Mapper;

use BgOauthProvider\Entity\AppNonce;
use DateTime;

interface AppNonceInterface
{

    /**
     * @param integer $appId
     * @param string $nonce
     * @param \DateTime $datetime
     * @return AppNonce
     */
    public function find($appId, $nonce, DateTime $datetime);

    /**
     * @param AppNonce $appNonce
     * @return null
     */
    public function save(AppNonce $appNonce);


}