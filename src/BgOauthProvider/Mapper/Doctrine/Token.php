<?php

namespace BgOauthProvider\Mapper\Doctrine;


use BgOauthProvider\Entity\AppInterface as AppEntity;
use BgOauthProvider\Entity\TokenInterface as TokenEntity;
use BgOauthProvider\Mapper\TokenInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use ZfcUser\Entity\UserInterface as UserEntity;

class Token implements TokenInterface, ObjectManagerAwareInterface
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

    /**
     * @param UserEntity $user
     * @param AppEntity $app
     * @return int
     */
    public function getCountOfAccessTokens(UserEntity $user, AppEntity $app)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getObjectManager()->createQueryBuilder();

        $qb->select("COUNT(t.id)")
            ->from('BgOauthProvider\Entity\Token', 't')
            ->where('t.type = ' . TokenEntity::TOKEN_ACCESS)
            ->andWhere('t.user = :user')
            ->andWhere('t.app = :app')
            ->setParameter('user', $user)
            ->setParameter('app', $app);

        return $qb->getQuery()->getSingleScalarResult();
    }
}