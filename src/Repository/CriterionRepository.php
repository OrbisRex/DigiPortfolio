<?php

namespace App\Repository;

use Doctrine\ORM\NoResultException;
use App\Entity\Criterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Criterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Criterion|null findOneBy(array $criterion, array $orderBy = null)
 * @method Criterion[]    findAll()
 * @method Criterion[]    findBy(array $criterion, array $orderBy = null, $limit = null, $offset = null)
 */
class CriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Criterion::class);
    }

    public function findByAssignment($assignmentId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT c.name, c.descriptors '
              .'FROM App:Criterion c '
              .'WHERE c.assignment = '.$assignmentId.' '
            );

        try {
            return $query->getResult();
        } catch (NoResultException) {
            return null;
        }
    }

    public function findCriteriaForAssignment($assignmentId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT c.name '
              .'FROM App:Criterion c '
              .'WHERE c.assignment = '.$assignmentId.' '
            );

        try {
            return $query->getResult();
        } catch (NoResultException) {
            return null;
        }
    }

    public function findNames($assignmentId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT c.name '
              .'FROM App:Criterion c '
              .'WHERE c.assignment = '.$assignmentId.' '
              .'GROUP BY c.name '
              .'ORDER BY c.name'
            );

        try {
            return $query->getResult();
        } catch (NoResultException) {
            return null;
        }
    }

    public function findAllNames($owner, $criterionId = false)
    {
        $entityManager = $this->getEntityManager();
        if ($criterionId) {
            $query = $entityManager->createQuery(
                'SELECT d.name '
                  .'FROM App:Criterion d '
                  .'WHERE d.person = App:Person '
                  .'AND d.criterion = '.$criterionId.' '
                  .'GROUP BY d.name '
                  .'ORDER BY d.name'
            );
        } else {
            $query = $entityManager->createQuery(
                'SELECT d.name '
                  .'FROM App:Criterion d '
                  .'WHERE d.person = :personId '
                  .'GROUP BY d.name '
                  .'ORDER BY d.name'
            );
            $query->setParameter('personId', $owner);
        }

        try {
            return $query->getResult();
        } catch (NoResultException) {
            return null;
        }
    }

    public function groupCriteriaByName($owner, $criterionId = false)
    {
        if (!$criterionId) {
            $criterionNames = $this->findAllNames($owner);

            if (!$criterionNames) {
                $criteria = false;
            } else {
                dump($criterionNames);
                foreach ($criterionNames as $name) {
                    $criteriaData = $this->findBy(['name' => $name['name']]);
                    dump($criteriaData);
                    // $descriptors[$name['name']] = $descriptorsData;
                    foreach ($criteriaData as $criterion) {
                        $criteria[$name['name']] = [$criterion->getName() => $criterion];
                    }
                }
            }
        } else {
            $criterionNames = $this->findAllNames($owner, $criteriaId);

            if (!$criterionNames) {
                $descriptors = false;
            } else {
                foreach ($criterionNames as $name) {
                    $criteriaData = $this->findBy([
                        'criteria' => $criteriaId, 'name' => $name['name'],
                    ]);

                    $criteria[$name['name']] = $criteriaData;
                }
            }
        }

        return $criteria;
    }

    // /**
    //  * @return Criterion[] Returns an array of Criterion objects
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
    public function findOneBySomeField($value): ?Criteria
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
