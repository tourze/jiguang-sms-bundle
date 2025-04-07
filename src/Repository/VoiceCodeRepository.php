<?php

namespace JiguangSmsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\VoiceCode;

/**
 * @method VoiceCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoiceCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoiceCode[] findAll()
 * @method VoiceCode[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoiceCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoiceCode::class);
    }

    /**
     * @return VoiceCode[]
     */
    public function findUnverifiedAndNotExpired(): array
    {
        $now = new \DateTimeImmutable();
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->where('c.verified = :verified')
            ->andWhere('c.createTime >= :expireTime')
            ->setParameter('verified', false)
            ->setParameter('expireTime', $now->modify('-1 day'))
            ->getQuery()
            ->getResult();
    }
}
