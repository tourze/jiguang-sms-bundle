<?php

namespace JiguangSmsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\TextCode;

/**
 * @method TextCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextCode[] findAll()
 * @method TextCode[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextCode::class);
    }

    /**
     * @return TextCode[]
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
