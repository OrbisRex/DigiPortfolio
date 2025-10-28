<?php

namespace App\Repository;

use Doctrine\ORM\NoResultException;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findAllStudents(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * 
                FROM Person p
                WHERE p.roles->> 0 = :role 
                ORDER BY p.name
               ";

        $resultSet = $conn->executeQuery($sql, ['role'=>'ROLE_STUDENT']);
        return $resultSet->fetchAllAssociative();
    }

    public function findStudentsFromGroup($group)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p '
              .'FROM App:Person u '
              .'WHERE JSON_GET_TEXT(p.roles, 1) = \'ROLE_STUDENT\' AND p.set = '.$group.' '
              .'ORDER BY p.name'
            );

        try {
            return $query->getResult();
        } catch (NoResultException) {
            return null;
        }
    }

    // /**
    //  * @return Person[] Returns an array of Person objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Person
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
