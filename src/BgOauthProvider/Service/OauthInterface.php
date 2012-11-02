<?php

namespace BgOauthProvider\Service;

use BgOauthProvider\Entity\AppNonce;
use BgOauthProvider\Entity\Token;

interface OauthInterface
{


    public function findNonce($appId, $nonce, \DateTime $dateTime);

    public function saveAppNonce(AppNonce $appNonce);

    public function saveToken(Token $token);

    public function findToken($token);

    public function updateToken(Token $token);

}