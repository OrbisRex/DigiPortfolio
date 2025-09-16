<?php

namespace App\Repository;

use App\Entity\AssignmentPerson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findLastAssignments($userId, $number)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT ap '
              .'FROM App:AssignmentPerson ap '
              .'JOIN ap.assignment a '
              .'WHERE ap.person = '.$userId.' '
              .'ORDER BY a.updatetime DESC'
            )
            ->setMaxResults($number);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException) {
            return null;
        }
    }

    public function findLastAssignmentsByTeacher($userId, $number)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT ap '
              .'FROM App:AssignmentPerson ap '
              .'JOIN ap.assignment a '
              .'WHERE ap.person = '.$userId.' '
              .'ORDER BY a.updatetime DESC'
            )
            ->setMaxResults($number);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException) {
            return null;
        }
    }

    public function findAssignmentsByTeacher($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT ap '
              .'FROM App:AssignmentPerson ap '
              .'JOIN ap.assignment a '
              .'WHERE ap.person = '.$userId.' '
              .'ORDER BY a.updatetime DESC'
            );

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException) {
            return null;
        }
    }

    public function findAssignmentsByStudent($userId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT ap '
              .'FROM App:AssignmentPerson ap '
              .'JOIN ap.assignment a '
              .'WHERE ap.person = '.$userId.' '
              .'ORDER BY a.updatetime DESC'
            );

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException) {
            return null;
        }
    }

    public function findAssignmentsByTeacherForSubject($userId, $subjectId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT ap '
              .'FROM App:AssignmentPerson ap '
              .'JOIN ap.assignment a '
              .'WHERE ap.person = '.$userId.' '
              .'AND a.subject = '.$subjectId.' '
              .'ORDER BY a.updatetime DESC'
            );

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException) {
            return null;
        }
    }

    public function findAssignmentsByStudentBySubject($userId, $subjectId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT ap '
              .'FROM App:AssignmentPerson ap '
              .'JOIN ap.assignment a'
              .'WHERE ap.person = '.$userId.' '
              .'AND a.subject = '.$subjectId.' '
              .'ORDER BY a.updatetime DESC'
            );

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException) {
            return null;
        }
    }

    public function findAssignmentsByStudentForTopic($userId, $topicId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT ap '
              .'FROM App:AssignmentPerson ap '
              .'JOIN ap.assignment a '
              .'WHERE ap.person = '.$userId.' '
              .'AND a.topic = '.$topicId.' '
              .'ORDER BY a.updatetime DESC'
            );

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException) {
            return null;
        }
    }

    public function findAssignmentsByTeacherForTopic($userId, $topicId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT ap '
              .'FROM App:AssignmentPerson ap '
              .'JOIN ap.assignment a '
              .'WHERE a.teacher = '.$userId.' '
              .'AND a.topic = '.$topicId.' '
              .'ORDER BY a.updatetime DESC'
            );

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException) {
            return null;
        }
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
