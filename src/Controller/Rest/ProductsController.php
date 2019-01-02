<?php

namespace App\Controller\Rest;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

class ProductsController extends FOSRestController
{

    /*
    TODO:
    getProduct
    aviable
    notaviable
    aviableover5
    */

    /**
     * @Rest\Post("/Products/{name}/{amount}", name="product_add")
     */
    public function addProduct(string $name,int $amount, ProductsRepository $repository): JsonResponse
    {
        $data = ['name' => $name, 'amount'=> $amount];
        $repository->addProduct($data);

        return new JsonResponse('added '.$name.' in amount:'.$amount);
    }

    /**
     * @Rest\Put("/Products/{id}/{name}/{amount}", name="product_edit")
     */
    public function editProduct(int $id, string $name,int $amount, ProductsRepository $repository): JsonResponse
    {
        $data = ['id'=> $id, 'name' => $name, 'amount'=> $amount];
        $repository->editProduct($data);

        return new JsonResponse('changed '.$name.' in amount:'.$amount);
    }

    /**
     * @Rest\Delete("/Products/{id}", name="product_delete")
     */
    public function removeProduct(int $id,ProductsRepository $repository): JsonResponse
    {
        $data = ['id'=> $id];
        $repository->deleteProduct($data);

        return new JsonResponse('delete '.$data['id']);
    }

}
