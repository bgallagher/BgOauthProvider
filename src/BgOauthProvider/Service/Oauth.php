<?php

namespace BgOauthProvider\Service;

use BgOauthProvider\Entity\AppNonce;
use BgOauthProvider\Entity\Token;
use Doctrine\ORM\EntityManager;
use BgOauthProvider\Mapper\AppNonceInterface as AppNonceMapper;
use BgOauthProvider\Mapper\TokenInterface as TokenMapper;

class Oauth implements OauthInterface
{

    /**
     * @var AppNonceMapper
     */
    protected $appNounceMapper;

    /**
     * @var TokenMapper
     */
    protected $tokenMapper;


    public function __construct(AppNonceMapper $appNounceMapper, TokenMapper $tokenMapper)
    {
        $this->appNounceMapper = $appNounceMapper;
        $this->tokenMapper = $tokenMapper;
    }

    public function findNonce($appId, $nonce, \DateTime $dateTime)
    {
        return $this->appNounceMapper->find($appId, $nonce, $dateTime);
    }

    public function saveAppNonce(AppNonce $appNonce)
    {
        $this->appNounceMapper->save($appNonce);
    }

    public function saveToken(Token $token)
    {
        return $this->tokenMapper->insert($token);
    }

    public function updateToken(Token $token)
    {
        $this->tokenMapper->update($token);
    }

    /**
     * @param string $token
     * @return \BgOauthProvider\Entity\TokenInterface|null
     */
    public function findToken($token)
    {
        return $this->tokenMapper->findByToken($token);
    }
}