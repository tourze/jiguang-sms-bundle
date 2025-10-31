<?php

namespace JiguangSmsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\TextCode;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;

/**
 * @extends ServiceEntityRepository<TextCode>
 */
#[AsRepository(entityClass: TextCode::class)]
class TextCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextCode::class);
    }

    public function save(TextCode $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TextCode $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return TextCode[]
     */
    public function findUnverifiedAndNotExpired(): array
    {
        $now = new \DateTimeImmutable();
        $qb = $this->createQueryBuilder('c');

        $result = $qb
            ->where('c.verified = :verified')
            ->andWhere('c.createTime >= :expireTime')
            ->setParameter('verified', false)
            ->setParameter('expireTime', $now->modify('-1 day'))
            ->getQuery()
            ->getResult()
        ;

        // PHPStan: explicitly cast to ensure correct type inference
        /** @var array<TextCode> $result */
        return $result;
    }
}
