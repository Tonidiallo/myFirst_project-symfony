<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personne>
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    //    /**
    //     * @return Personne[] Returns an array of Personne objects
    //     */
        public function findPersonneByAgeInterval($ageMin, $ageMax)
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.age >= :ageMin and p.age <= :ageMax')
                ->setParameter('ageMin',$ageMin)
                ->setParameter('ageMax',$ageMax)
                ->getQuery()
                ->getResult();
        }

    //    public function findOneBySomeField($value): ?Personne
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
