<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Trip;
use App\Entity\User;
use App\Search\SearchForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Response;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Trip $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Trip $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getAllTrip()
    {
        $queryBuilder=$this->createQueryBuilder('trip');
        $queryBuilder ->andWhere('trip.name LIKE ?1' );
        $query=$queryBuilder->getQuery();


        return $query;
    }

//    public function search($data, $data2)
//    {
//
//
//      return $this->createQueryBuilder('t')
//        //Nom de la sortie
//        ->andWhere('t.name LIKE ?1')
//        ->setParameter(1,'%'.$data.'%')
//          //Campus
//        ->andWhere('t.location = ?2')
//        ->setParameter(2,$data2)
//
//        ->getQuery()
//        ->execute();
//    }

    public function search($value){
        $query = $this ->createQueryBuilder('trip');

        if(!empty($value['site'])){
            $query = $query->andWhere('trip.location = ?1')
                 ->setParameter(1,$value['site']);
        }
        if(!empty($value['name'])){
            $query = $query->andWhere('trip.name LIKE ?2')
                ->setParameter(2,'%'.$value['name'] .'%');
        }

        return $query -> getQuery()->getResult();

    }
 //Piste pour les dates
//->where("m.createdAt > ?1")
//->andWhere("m.createdAt < ?2")
//->setParameter(1, $beginDate)
//->setParameter(2, $endDate)


}
