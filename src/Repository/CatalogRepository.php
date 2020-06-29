<?php

namespace App\Repository;

use App\Entity\Catalog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Catalog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Catalog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Catalog[]    findAll()
 * @method Catalog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Catalog::class);
    }

    /**
     * @return Catalog[]
     */
    public function listCatalogSup(){
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT c.id, c.name, c.description, c.created_date, c.update_date, c.created_user, c.update_user, COUNT(*)
            FROM catalog c
            INNER JOIN article a
            WHERE (c.id = a.catalog_id)
            GROUP BY c.id, c.name, c.description, c.created_date, c.update_date, c.created_user, c.update_user
            HAVING COUNT(*)>1
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    /**
     * @return Catalog[]
     */
    public function listCatalogName(){
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT name
            FROM catalog 
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }


        /**
     * @return Catalog[]
     */
    public function CatalogIdByName($value){
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT id FROM `catalog` WHERE name='.$value.'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }


    // /**
    //   * @return Catalog[] Returns an array of Catalog objects
    //   *
    // */
    // public function findMore(){
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('c.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }


    /*
    public function findOneBySomeField($value): ?Catalog
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
