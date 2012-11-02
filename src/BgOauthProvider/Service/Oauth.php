<?php

namespace BgOauthProvider\Service;

use Doctrine\ORM\EntityManager;
use BgOauthProvider\Entity\AppNonce;
use BgOauthProvider\Entity\Token;

class Oauth implements OauthInterface
{

    protected $em;


    public function __construct(EntityManager $em)
    {
        $this->setEm($em);
    }

    public function findNonce($appId, $nonce, \DateTime $dateTime)
    {
        return $this->getEm()->getRepository('BgOauthProvider\Entity\AppNonce')
            ->findOneBy(array('app' => $appId, 'nonce' => $nonce, 'timestamp' => $dateTime));
    }

    public function saveAppNonce(AppNonce $appNonce)
    {
        $this->getEm()->persist($appNonce);
        $this->getEm()->flush();
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

    public function getEm()
    {
        return $this->em;
    }

    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }
}