<?php

namespace App\Repository;

use App\Entity\UserLesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for the UserLesson entity.
 *
 * Provides methods to query the database for users' lessons.
 *
 * @extends ServiceEntityRepository<UserLesson>
 *
 * @method UserLesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLesson[]    findAll()
 * @method UserLesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLessonRepository extends ServiceEntityRepository
{
    /**
     * UserLessonRepository constructor.
     *
     * @param ManagerRegistry $registry Doctrine entity manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLesson::class);
    }

    // Custom methods (uncomment and adapt as needed):

    // /**
    //  * @return UserLesson[] Returns an array of UserLesson objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('u.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // /**
    //  * @return UserLesson|null Returns a single UserLesson object or null
    //  */
    // public function findOneBySomeField($value): ?UserLesson
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
