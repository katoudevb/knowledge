<?php

namespace App\Repository;

use App\Entity\Theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for the Theme entity.
 *
 * Provides methods to query the database for themes.
 *
 * @extends ServiceEntityRepository<Theme>
 *
 * @method Theme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Theme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Theme[]    findAll()
 * @method Theme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeRepository extends ServiceEntityRepository
{
    /**
     * ThemeRepository constructor.
     *
     * @param ManagerRegistry $registry Doctrine entity manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Theme::class);
    }

    // Custom methods (uncomment and adapt as needed):

    // /**
    //  * @return Theme[] Returns an array of Theme objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('t')
    //         ->andWhere('t.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('t.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // /**
    //  * @return Theme|null Returns a single Theme object or null
    //  */
    // public function findOneBySomeField($value): ?Theme
    // {
    //     return $this->createQueryBuilder('t')
    //         ->andWhere('t.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
