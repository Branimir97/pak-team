<?php

namespace App\Repository;

use App\Entity\SoldVehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SoldVehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoldVehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoldVehicle[]    findAll()
 * @method SoldVehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoldVehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoldVehicle::class);
    }
}
