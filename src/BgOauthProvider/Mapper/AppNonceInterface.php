<?php

namespace BgOauthProvider\Mapper;

use BgOauthProvider\Entity\AppNonce as AppNonceEntity;
use DateTime;

interface AppNonceInterface
{

    /**
     * @param integer $appId
     * @param string $nonce
     * @param \DateTime $datetime
     * @return AppNonceEntity
     */
    public function find($appId, $nonce, DateTime $dateTime);

    /**
     * @param AppNonceEntity $appNonce
     * @return null
     */
    public function save(AppNonceEntity $appNonce);


}