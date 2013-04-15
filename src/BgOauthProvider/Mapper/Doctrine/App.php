<?php

namespace BgOauthProvider\Mapper\Doctrine;

use BgOauthProvider\Entity\App as AppEntity;
use BgOauthProvider\Mapper\AppInterface;
use Doctrine\Common\Persistence\ObjectManager;

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
     * @inheritdoc
     */
    public function findAppByConsumerKey($consumerKey)
    {
        return $this->getObjectManager()->getRepository('BgOauthProvider\Entity\App')
            ->findOneBy(array('consumer_key' => $consumerKey));
    }

    /**
     * @inheritdoc
     */
    public function findAppById($id)
    {
        return $this->getObjectManager()->getRepository('BgOauthProvider\Entity\App')->find($id);
    }

    /**
     * @param AppEntity $app
     * @param bool $flush
     */
    public function createApp(AppEntity $app, $flush = true)
    {
        $this->getObjectManager()->persist($app);

        if ($flush) {
            $this->getObjectManager()->flush();
        }
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