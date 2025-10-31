<?php

namespace JiguangSmsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\VoiceCode;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;

/**
 * @extends ServiceEntityRepository<VoiceCode>
 */
#[AsRepository(entityClass: VoiceCode::class)]
class VoiceCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoiceCode::class);
    }

    public function save(VoiceCode $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VoiceCode $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return VoiceCode[]
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
        /** @var array<VoiceCode> $result */
        return $result;
    }
}
