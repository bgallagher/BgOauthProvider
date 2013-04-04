<?php

namespace BgOauthProvider\Mapper\Doctrine;


use BgOauthProvider\Mapper\TokenInterface;
use \BgOauthProvider\Entity\TokenInterface as TokenEntity;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class Token implements TokenInterface, ObjectManagerAwareInterface
{

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param TokenEntity $token
     * @return null
     */
    public function insert(TokenEntity $token)
    {
        $this->getObjectManager()->persist($token);
        $this->getObjectManager()->flush();
    }

    /**
     * @param TokenEntity $token
     * @return null
     */
    public function update(TokenEntity $token)
    {
        $this->insert($token);
    }

    /**
     * @param string $token
     * @return TokenEntity|null
     */
    public function findByToken($token)
    {
        return $this->getObjectManager()->getRepository('BgOauthProvider\Entity\Token')
            ->findOneBy(array('token' => $token));
    }

    /**
     * Set the object manager
     *
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Get the object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}