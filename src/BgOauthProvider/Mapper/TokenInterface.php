<?php

namespace BgOauthProvider\Mapper;

use \BgOauthProvider\Entity\TokenInterface as TokenEntity;


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


}