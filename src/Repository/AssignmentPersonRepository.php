<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\AssignmentPerson;
use App\Entity\Assignment;
use App\Entity\Person;
use App\Entity\Subject;
use App\Entity\Topic;

/**
 * @method AssignmentPerson|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssignmentPerson|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssignmentPerson[]    findAll()
 * @method AssignmentPerson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssignmentPersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssignmentPerson::class);
    }

    public function findLastAssignments(Person $person, int $number): mixed
    {
        $query = $this->createQueryBuilder('ap')
            ->select('a.id, a.name, s.name subject, t.name topic')
            ->join('ap.assignment', 'a')
            ->join('a.subject', 's')
            ->join('a.topic', 't')
            ->where('ap.person = :person')
            ->setParameter('person', $person)
            ->orderBy('a.updatetime', 'DESC')
            ->setMaxResults($number)
        ;

        return $query->getQuery()->getResult();
    }

    public function findAssignmentsByPerson(Person $person): mixed
    {
        $query = $this->createQueryBuilder('ap')
            ->select('a.id, a.name, s.name subject, t.name topic')
            ->join('ap.assignment', 'a')
            ->join('a.subject', 's')
            ->join('a.topic', 't')
            ->where('ap.person = :person')
            ->setParameter('person', $person)
            ->orderBy('a.updatetime', 'DESC')
        ;

        return $query->getQuery()->getResult();
    }

    public function findStudentsByAssignment(Assignment $assignment): mixed 
    {
        $query = $this->createQueryBuilder('ap')
            ->select('p.id, p.name, p.email')
            ->join('ap.person', 'p')
            ->where('ap.assignment = :assignment')
            ->setParameter('assignment', $assignment)
            ->orderBy('p.name', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }

    public function findAssignmentsByPersonForSubject(Person $person, Subject $subject): mixed
    {
        $query = $this->createQueryBuilder('ap')
            ->select('a.id, a.name, s.name subject')
            ->join('ap.assignment', 'a')
            ->join('a.subject', 's')
            ->where('ap.person = :person')
            ->setParameter('person', $person)
            ->where('a.subject = :subject')
            ->setParameter('subject', $subject)
            ->orderBy('a.updatetime', 'DESC')
        ;

        return $query->getQuery()->getResult();
    }

    public function findAssignmentsByPersonForTopic(Person $person, Topic $topic): mixed
    {
        $query = $this->createQueryBuilder('ap')
            ->select('a.id, a.name, t.name topic')
            ->join('ap.assignment', 'a')
            ->join('a.topic', 't')
            ->where('ap.person = :person')
            ->setParameter('person', $person)
            ->where('a.topic = :topic')
            ->setParameter('topic', $topic)
            ->orderBy('a.updatetime', 'DESC')
        ;

        return $query->getQuery()->getResult();
    }


    // /**
    //  * @return AssignmentPerson[] Returns an array of AssignmentPerson objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssignmentPerson
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
