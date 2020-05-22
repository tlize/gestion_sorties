<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findPageAcceuil(SearchData $search):array
    {

        $query = $this
            ->createQueryBuilder('s')

            ->join('s.participants', 'par')
            ->join('s.organisateur', 'org')
            ->join('s.campus', 'cam')
            ->join('s.etat', 'etat')
            ->join('s.lieu','lieu')
            ->addSelect('par')
            ->addSelect('org')
            ->addSelect('etat')
            ->addSelect('cam')
            ->addSelect('lieu');

        if(!empty($search->q)){

            $query = $query
                ->andWhere('s.nom LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }
        if(!empty($search->campus)){
            $query = $query
                ->andWhere('cam.id IN (:campus)')
                ->setParameter('campus', $search->campus);
        }
            return $query->getQuery()->getResult()
            ;

    }


    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
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
    public function findOneBySomeField($value): ?Sortie
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
