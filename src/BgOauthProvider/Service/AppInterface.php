<?php

namespace BgOauthProvider\Service;

interface AppInterface
{
    public function findAppByConsumerKey($consumerKey);
}
