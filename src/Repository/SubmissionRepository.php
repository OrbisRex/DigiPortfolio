<?php

namespace App\Repository;

use App\Entity\Submission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Parameter;


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

    public function findLastSubmissions($person, $number)
    {

        $query = $this->createQueryBuilder('s')
            ->join('s.assignment', 'a')
            ->where('s.people = ?1')
            ->setParameter(1, $person)
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
            ->setParameters(
                new ArrayCollection([
                new Parameter('1', $setId),
                new Parameter('2', array_values($people))
                ]))
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
            ->where('a.people IN (:people)')
            ->setParameter('people', array_values($people))
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
