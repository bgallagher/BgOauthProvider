<?php

namespace BgOauthProvider\Service;

use BgOauthProvider\Entity\App as AppEntity;
use Doctrine\ORM\EntityManager;

class App implements AppInterface
{

    public $serviceLocator;
    public $em;

    public function __construct(EntityManager $em)
    {
        $this->setEm($em);
    }

    public function createApp(AppEntity $app, $flush = true)
    {
        $em = $this->getEm();

        $em->persist($app);

        if ($flush) {
            $em->flush();
        }
    }

    public function findAppByConsumerKey($consumerKey)
    {
        return $this->getEm()->getRepository('BgOauthProvider\Entity\App')->findOneBy(array('consumer_key' => $consumerKey));
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
