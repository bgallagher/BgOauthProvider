<?php

namespace BgOauthProvider\Mapper\Doctrine;

use BgOauthProvider\Mapper\AppInterface;
use BgOauthProvider\Entity\App as AppEntity;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class App implements AppInterface
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
     * @param string $consumerKey
     * @return AppEntity
     */
    public function findAppByConsumerKey($consumerKey)
    {
        return $this->getObjectManager()->getRepository('BgOauthProvider\Entity\App')
            ->findOneBy(array('consumer_key' => $consumerKey));
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