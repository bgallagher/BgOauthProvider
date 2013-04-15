<?php

namespace BgOauthProvider\Mapper;

use BgOauthProvider\Entity\AppInterface as AppEntity;

interface AppInterface {

    /**
     * @param string $consumerKey
     * @return null|AppEntity
     */
    public function findAppByConsumerKey($consumerKey);

    /**
     * @param int $id
     * @return null|AppEntity
     */
    public function findAppById($id);

}