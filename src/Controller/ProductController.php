<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Entity\Seller;
use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/api", name="api_")
 */

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="get_products", methods={"GET"})
     */
    public function getProducts(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Product::class);
        $products = $repository->findAll();
        
        $data = [];
        
        foreach ($products as $product) {
            $categories = [];
            foreach($product->getProductCateg() as $categorie){ //get array categories
                   $categories[]=$categorie->getLibelle();
            }
           $data[] = [
               'id' => $product->getId(),
               'libelle' => $product->getlibelle(),
               'price' => $product->getPrice(),
               'stock'=> $product->getStock(),
               'description' => $product->getDescription(),
               'categorie' => $categories,
               'seller' => $product->getProductSel()->getLibelle()
           ];
        }
 
        return $this->json($data);
    }
    /**
     * @Route("/products", name="add_product", methods={"POST"})
     */
    public function createProduct(ManagerRegistry $doctrine,Request $request): Response
    { 
        $entityManager = $doctrine->getManager();
        $product = new Product();
        $product->setLibelle($request->request->get('libelle'));
        $product->setPrice($request->request->get('price'));
        $product->setStock($request->request->get('stock'));
        $product->setDescription($request->request->get('description'));
        
        $seller = $doctrine->getRepository(Seller::class)->find($request->request->get('sellerId'));
        $product ->setProductSel($seller);
        
        $categoriesId = $request->request->all("categoriesId");
        foreach($categoriesId as $categorieId){
            $categorie = $doctrine->getRepository(Categorie::class)->find($categorieId);
            $product->addProductCateg($categorie);   
        }

        $entityManager->persist($product);
        $entityManager->flush();
 
        return $this->json("create");
    }

     /**
     * @Route("/products/{id}", name="GET_product", methods={"GET"})
     */
    public function getProductById(ManagerRegistry $doctrine, int $id): Response
    {
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->find($id);
        if (!$product){
            throw $this->createNotFoundException('No categorie found for this id');
        }
        return $this->json($product->getLibelle());
    }
    
     /**
     * @Route("/products/{id}", name="delete_product", methods={"DELETE"})
     */
    public function deleteCategorie(ManagerRegistry $doctrine,int $id): Response
    {
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->find($id);
        if (!$product) {
            return $this->json('No orderState found for id' . $id, 404);
        }
        $repository->remove($product);
        $repository->flush();
 
        return $this->json('Deleted an orderState successfully ');
    }



}
