<?php

namespace BgOauthProvider\Service;

use BgOauthProvider\Entity\AppNonce;
use BgOauthProvider\Entity\Token;
use Doctrine\ORM\EntityManager;
use BgOauthProvider\Mapper\AppNonceInterface as AppNonceMapper;

class Oauth implements OauthInterface
{

    /**
     * @var AppNonceMapper
     */
    protected $appNounceMapper;


    public function __construct(AppNonceMapper $appNounceMapper, EntityManager $em)
    {
        $this->setEm($em);

        $this->appNounceMapper = $appNounceMapper;
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
        return $this->updateToken($token);
    }

    public function updateToken(Token $token)
    {
        $this->getEm()->persist($token);
        $this->getEm()->flush();
    }

    public function findToken($token)
    {
        return $this->getEm()->getRepository('BgOauthProvider\Entity\Token')->findOneBy(array('token' => $token));
    }






    /**
     * @depricated
     */
    protected $em;

    /**
     * @depricated
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @depricated
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }
}