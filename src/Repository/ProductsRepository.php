<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{


    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(RegistryInterface $registry, ObjectManager $manager)
    {
        parent::__construct($registry, Products::class);

        $this->manager = $manager;
    }

    // /**
    //  * @return Products[] Returns an array of Products objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Products
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param $data
     * @return bool
     */
    public function addProduct($data){

        $product = new Products();
        $product->setName($data['name']);
        $product->setAmount($data['amount']);
        $this->manager->persist($product);
        $this->manager->flush();

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    public function editProduct($data){

        $product = $this->find($data['id']);
        $product->setName($data['name']);
        $product->setAmount($data['amount']);
        $this->manager->persist($product);
        $this->manager->flush();

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    public function deleteProduct($data){
        
        $product = $this->find($data['id']);
        $this->manager->remove($product);
        $this->manager->flush();

        return true;
    }

}
