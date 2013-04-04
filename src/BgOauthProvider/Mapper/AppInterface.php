<?php

namespace BgOauthProvider\Mapper;

use BgOauthProvider\Entity\AppInterface as AppEntity;

interface AppInterface {

    /**
     * @param string $consumerKey
     * @return AppEntity
     */
    public function findAppByConsumerKey($consumerKey);

}