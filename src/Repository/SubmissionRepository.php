<?php

namespace App\Repository;

use App\Entity\Submission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Submission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Submission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Submission[]    findAll()
 * @method Submission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Submission::class);
    }

    public function findLastSubmissions($people, $number)
    {

        $query = $this->createQueryBuilder('s')
            ->join('s.assignment', 'a')
            ->where('s.people IN (?1)')
            ->setParameters([1 => array_values($people)])
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->setMaxResults($number);
        ;

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return NULL;
        }        
    }

    public function findBySet($setId, $people)
    {
        $query = $this->createQueryBuilder('s')
            ->join('s.assignment', 'a')
            ->where('a.set = ?1')
            ->andWhere('s.people IN (?2)')
            ->setParameters([1 => $setId, 2 => array_values($people)])
            ->orderBy('s.name', 'ASC')
            ->getQuery()
        ;

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return NULL;
        }        
    }

    public function findByPeople(array $people)
    {
        $query = $this->createQueryBuilder('s')
            ->join('s.assignment', 'a')
            ->andWhere('a.people IN (:people)')
            ->setParameters(['people', array_values($people)])
            ->orderBy('s.name', 'ASC')
            ->getQuery()
        ;

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return NULL;
        }        
    }

    // /**
    //  * @return Submission[] Returns an array of Submission objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Submission
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
