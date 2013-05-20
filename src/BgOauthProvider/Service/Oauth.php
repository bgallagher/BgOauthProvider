<?php

namespace BgOauthProvider\Service;

use BgOauthProvider\Entity\App;
use BgOauthProvider\Entity\AppNonce;
use BgOauthProvider\Entity\Token;
use BgOauthProvider\Mapper\AppInterface as AppMapper;
use BgOauthProvider\Mapper\AppNonceInterface as AppNonceMapper;
use BgOauthProvider\Mapper\TokenInterface as TokenMapper;
use DateTime;
use Doctrine\ORM\EntityManager;
use ZfcUser\Entity\UserInterface as User;

class Oauth
{

    /**
     * @var AppNonceMapper
     */
    protected $appNounceMapper;

    /**
     * @var TokenMapper
     */
    protected $tokenMapper;

    /**
     * @var AppMapper
     */
    protected $appMapper;

    /**
     * @param AppNonceMapper $appNounceMapper
     * @param TokenMapper $tokenMapper
     */
    public function __construct(AppNonceMapper $appNounceMapper, TokenMapper $tokenMapper, AppMapper $appMapper)
    {
        $this->appNounceMapper = $appNounceMapper;
        $this->tokenMapper = $tokenMapper;
        $this->appMapper = $appMapper;
    }

    /**
     * @param int $appId
     * @param string $nonce
     * @param DateTime $dateTime
     * @return AppNonce
     */
    public function findNonce($appId, $nonce, DateTime $dateTime)
    {
        return $this->appNounceMapper->find($appId, $nonce, $dateTime);
    }

    /**
     * @param AppNonce $appNonce
     */
    public function saveAppNonce(AppNonce $appNonce)
    {
        $this->appNounceMapper->save($appNonce);
    }

    /**
     * @param Token $token
     * @return null
     */
    public function saveToken(Token $token)
    {
        return $this->tokenMapper->insert($token);
    }

    /**
     * @param Token $token
     */
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

    /**
     * @param $consumerKey
     * @return App|null
     */
    public function findAppByConsumerKey($consumerKey)
    {
        return $this->appMapper->findAppByConsumerKey($consumerKey);
    }

    /**
     * @param User $user
     * @param App $app
     * @return bool
     */
    public function isRepeatAuthorization(User $user, App $app)
    {
        $isRepeat = false;

        $count = $this->tokenMapper->getCountOfAccessTokens($user, $app);

        if(is_numeric($count) && $count > 0) {
            $isRepeat = true;
        }

        return $isRepeat;
    }
}