<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('v')
            ->where('v.visibility = :visibility')
            ->andWhere('v.status = :statusStock')
            ->setParameter('visibility', 1)
            ->setParameter('statusStock', "Dostupno")
            ->orderBy('v.id', 'DESC');
    }

    public function getMaxRes()
    {
        return $this->createQueryBuilder('v')
            ->where('v.visibility = :visibility')
            ->andWhere('v.status = :statusStock')
            ->setParameter('visibility', 1)
            ->setParameter('statusStock', "Dostupno")
            ->select('count(v.id)')->getQuery()->getSingleScalarResult();
    }

    public function findAllAsArray() {
        return $this->createQueryBuilder('v')
            ->orderBy('v.id', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    public function findByFilter($filter) {
        if($filter == "Sva vozila") {
            return $this->findAllAsArray();
        } else {
            return $this->createQueryBuilder('v')
                ->where('v.status = :status')
                ->setParameter('status', $filter)
                ->getQuery()
                ->getArrayResult();
        }
    }
}
