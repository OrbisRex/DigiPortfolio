<?php

namespace App\Repository;

use Doctrine\ORM\NoResultException;
use App\Entity\Submission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Set;
use App\Entity\Person;

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
            ->select('s.id, s.name')
            ->join('s.people', 'p')
            ->where('p.id = :person')
            ->setParameter('person', $person)
            ->orderBy('s.name', 'ASC')
            ->setMaxResults($number)
        ;

        return $query->getQuery()->getResult();
    }

    public function findBySet(Set $set, Person $person)
    {
        $query = $this->createQueryBuilder('s')
            ->join('s.people', 'p')
            ->join('s.assignment', 'a')
            ->join('a.set', 'st')
            ->where('st.id = :set')
            ->andWhere('p.id = :person')
            ->setParameters(
                new ArrayCollection([
                    new Parameter('set', $set),
                    new Parameter('person', $person),
                ])
            )
            ->orderBy('s.name', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }

    public function findByPeople(array $people)
    {
        // Guard: empty input => no results
        if (empty($people)) {
            return [];
        }

        // Submissions have a ManyToMany `people` relation (App\Entity\Person).
        // Query submissions that are linked to any of the provided person ids.
        $query = $this->createQueryBuilder('s')
            ->join('s.people', 'p')
            ->where('p.id IN (:people)')
            ->setParameter('people', array_values($people))
            ->orderBy('s.name', 'ASC')
        ;

        return $query->getQuery()->getResult();
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
