<?php

namespace App\Repository;

use App\Entity\Fds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fds>
 *
 * @method Fds|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fds|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fds[]    findAll()
 * @method Fds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FdsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fds::class);
    }

//    /**
//     * @return Fds[] Returns an array of Fds objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fds
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
