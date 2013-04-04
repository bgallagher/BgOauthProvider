<?php

namespace BgOauthProvider\Mapper\Doctrine;

use BgOauthProvider\Entity\AppNonce as AppNonceEntity;
use BgOauthProvider\Mapper\AppNonceInterface;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class AppNonce implements AppNonceInterface, ObjectManagerAwareInterface
{

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param integer $appId
     * @param string $nonce
     * @param \DateTime $datetime
     * @return AppNonceEntity
     */
    public function find($appId, $nonce, DateTime $dateTime)
    {
        return $this->getObjectManager()->getRepository('BgOauthProvider\Entity\AppNonce')
            ->findOneBy(array('app' => $appId, 'nonce' => $nonce, 'timestamp' => $dateTime));
    }

    /**
     * @param AppNonceEntity $appNonce
     * @return null
     */
    public function save(AppNonceEntity $appNonce)
    {
        $this->getObjectManager()->persist($appNonce);
        $this->getObjectManager()->flush();
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