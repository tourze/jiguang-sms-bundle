<?php

namespace JiguangSmsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\AccountBalance;

/**
 * @method AccountBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountBalance[] findAll()
 * @method AccountBalance[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountBalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountBalance::class);
    }
}
