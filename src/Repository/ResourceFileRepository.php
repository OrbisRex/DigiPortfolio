<?php

namespace App\Repository;

use App\Entity\ResourceFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResourceFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResourceFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResourceFile[]    findAll()
 * @method ResourceFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResourceFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResourceFile::class);
    }

    public function findFilesByType($type, $owner, $limit = 100)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT f '
              .'FROM App:ResourceFile f '
              .'WHERE f.owner = :owner '
              .'AND f.type LIKE :type '
              .'ORDER BY f.updatetime DESC'
            )
            ->setParameter('owner', $owner)
            ->setParameter('type', $type)
            ->setMaxResults($limit);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findOtherFilesThen($type, $owner, $limit = 100)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT f '
              .'FROM App:ResourceFile f '
              .'WHERE f.owner = :owner '
              .'AND f.type NOT LIKE :type '
              .'ORDER BY f.updatetime DESC'
            )
            ->setParameter('owner', $owner)
            ->setParameter('type', $type)
            ->setMaxResults($limit);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    // /**
    //  * @return ResourceFile[] Returns an array of File objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?File
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
