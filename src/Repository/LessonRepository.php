<?php

namespace App\Repository;

use App\Entity\Lesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for the Lesson entity.
 *
 * Provides methods to query the database for lessons.
 *
 * @extends ServiceEntityRepository<Lesson>
 *
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonRepository extends ServiceEntityRepository
{
    /**
     * LessonRepository constructor.
     *
     * @param ManagerRegistry $registry Doctrine entity manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    // Example custom methods (uncomment and adapt as needed):

    // /**
    //  * @return Lesson[] Returns an array of Lesson objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('l')
    //         ->andWhere('l.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('l.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // /**
    //  * @return Lesson|null Returns a single Lesson object or null
    //  */
    // public function findOneBySomeField($value): ?Lesson
    // {
    //     return $this->createQueryBuilder('l')
    //         ->andWhere('l.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
