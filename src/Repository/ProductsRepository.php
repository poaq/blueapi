<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
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

    /**
     * @param $criteria
     * @return Products[]
     */
    public function findByKey($criteria){

        $result = [];

        switch ($criteria){
            case 0:
                $products = $this->findAllProductsWithAmountZero();
                break;
            case 1:
                $products = $this->findAllProductsOrderedByNewest();
                break;
            case 2:
                $products = $this->findAllProductsWithAmountOverFive();
                break;
            default:
                $products = $this->findAllProductsOrderedByNewest();
        }


        foreach ($products as $product) {
            $result[$product->getId()] = [
                'name' => $product->getName(),
                'amount' => $product->getAmount()
            ];

        }

        return $result;
    }

    /**
     * @return Products[]
     */
    private function findAllProductsOrderedByNewest()
    {
        return $this->addIsAmountNotNullQueryBuilder()
            ->orderBy('p.id','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Products[]
     */
    private function findAllProductsWithAmountOverFive()
    {
        return $this->addIsAmountHigherThanFive()
            ->orderBy('p.id','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Products[]
     */
    private function findAllProductsWithAmountZero()
    {
        return $this->addIsAmountIsNullQueryBuilder()
            ->orderBy('p.id','DESC')
            ->getQuery()
            ->getResult()
            ;
    }


    private function addIsAmountNotNullQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('p.amount > 0');
    }

    private function addIsAmountHigherThanFive(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('p.amount > 5');
    }

    private function addIsAmountIsNullQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('p.amount = 0');
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('p');
    }

}
