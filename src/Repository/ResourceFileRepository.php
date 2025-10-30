<?php

namespace App\Repository;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\ResourceFile;
use App\Entity\Person;

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

    public function findFilesByType(string $type, Person $owner, int $limit = 100): mixed
    {
        $query = $this->createQueryBuilder('f')
            ->where('f.owner = :owner')
            ->andWhere('f.type LIKE :type')
            ->setParameters(
                new ArrayCollection([
                    new Parameter('type', $type),
                    new Parameter('owner', $owner),
                ])
            )
            ->orderBy('f.updatetime', 'DESC')
            ->setMaxResults($limit)        
        ;

        return $query->getQuery()->getResult();
    }

    public function findOtherFilesThen(string $type, Person $owner, int $limit = 100): mixed
    {
        $query = $this->createQueryBuilder('f')
            ->where('f.owner = :owner')
            ->andWhere('f.type NOT LIKE :type')
            ->setParameters(
                new ArrayCollection([
                    new Parameter('type', $type),
                    new Parameter('owner', $owner),
                ])
            )
            ->orderBy('f.updatetime', 'DESC')
            ->setMaxResults($limit)        
        ;

        return $query->getQuery()->getResult();
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
