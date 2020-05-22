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

    public function findPageAcceuil(SearchData $search,  $userid):array
    {

        $query = $this
            ->createQueryBuilder('s')


            ->join('s.campus', 'cam')
            ->leftjoin('s.participants','participant')
            ->join('s.organisateur','organisateur')
            ->join('s.etat', 'etat')
            ->join('s.lieu','lieu')
            ->addSelect('participant')
            ->addSelect('organisateur')
            ->addSelect('etat')
            ->addSelect('cam')
            ->addSelect('lieu')
            ->andWhere("DATE_ADD(s.date_heure_debut, 1, 'month') > CURRENT_DATE()");

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
        if (!empty($search->dateMin)){
            $query = $query
                ->andWhere('s.date_heure_debut >=:dateMin')
                ->setParameter('dateMin',$search->dateMin);
        }
        if (!empty($search->dateMax)){
            $query = $query
                ->andWhere('s.date_heure_debut <=:dateMax')
                ->setParameter('dateMax',$search->dateMax);
        }
        if(!empty($search->organisateur)){
            $query = $query
                ->andWhere('s.organisateur = :userid')
                ->setParameter('userid', $userid)
                ;
        }
        if(!empty($search->inscrit)) {
            $query = $query
                ->andWhere(':userid MEMBER OF s.participants' )
                ->setParameter('userid', $userid);

        }
        if(!empty($search->pasInscrit)) {
            $query = $query
                ->andWhere(':userid NOT MEMBER OF s.participants' )
                ->setParameter('userid', $userid);

        }
        if(!empty($search->passees)){
            $query = $query
                ->andWhere('s.etat = 5')
            ;
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
