<?php

namespace BgOauthProvider\Mapper;

use BgOauthProvider\Entity\TokenInterface as TokenEntity;
use ZfcUser\Entity\UserInterface as UserEntity;
use BgOauthProvider\Entity\AppInterface as AppEntity;


interface TokenInterface
{

    /**
     * @param TokenEntity $token
     * @return null
     */
    public function insert(TokenEntity $token);

    /**
     * @param TokenEntity $token
     * @return null
     */
    public function update(TokenEntity $token);

    /**
     * @param string $token
     * @return TokenEntity|null
     */
    public function findByToken($token);

    /**
     * @param UserEntity $user
     * @param AppEntity $app
     * @return int
     */
    public function getCountOfAccessTokens(UserEntity $user, AppEntity $app);
}