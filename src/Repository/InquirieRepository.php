<?php

namespace App\Repository;

use App\Entity\Inquirie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inquirie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inquirie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inquirie[]    findAll()
 * @method Inquirie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InquirieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inquirie::class);
    }
}
