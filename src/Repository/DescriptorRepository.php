<?php

namespace App\Repository;

use App\Entity\Descriptor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Descriptor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Descriptor|null findOneBy(array $descriptor, array $orderBy = null)
 * @method Descriptor[]    findAll()
 * @method Descriptor[]    findBy(array $descriptor, array $orderBy = null, $limit = null, $offset = null)
 */
class DescriptorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Descriptor::class);
    }

    // /**
    //  * @return Descriptor[] Returns an array of Descriptor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Descriptor
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
