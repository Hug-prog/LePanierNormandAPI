<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileUploader;



/**
 * @Route("/api", name="api_")
 */

class CategorieController extends AbstractController
{
    /**
     * @Route("/categories", name="get_categories", methods={"GET"})
     */
    public function getCategorie(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Categorie::class);
        $categories = $repository->findAll();
        
        $data = [];
 
        foreach ($categories as $categorie) {
           $data[] = [
               'id' => $categorie->getId(),
               'libelle' => $categorie->getLibelle(),
           ];
        }
 
 
        return $this->json($data);
    }
    /**
     * @Route("/categories", name="add_categorie", methods={"POST"})
     */
    public function createCategorie(ManagerRegistry $doctrine,Request $request,FileUploader $fileUploader): Response
    { 
        $entityManager = $doctrine->getManager();
        $categorie = new Categorie();
        $categorie->setLibelle($request->request->get('libelle'));
        $file = $request->files->get('image');
        $fileName = $fileUploader->uploadImage($file,'/categorie');
        $categorie->setImage($fileName);

        $entityManager->persist($categorie);
        $entityManager->flush();
 
        return $this->json($categorie->getLibelle());
    }

     /**
     * @Route("/categories/{id}", name="GET_categorie", methods={"GET"})
     */
    public function getCategorieById(ManagerRegistry $doctrine, int $id): Response
    {
        $repository = $doctrine->getRepository(Categorie::class);
        $categorie = $repository->find($id);
        if (!$categorie){
            throw $this->createNotFoundException('No categorie found for this id');
        }
        return $this->json($categorie->getLibelle());
    }

    /**
     * @Route("/categories/{id}", name="GET_categorie", methods={"GET"})
    */
    public function  getProductsByCategoryId(ManagerRegistry $doctrine, int $id): Response
    {
        $repository = $doctrine->getRepository(Categorie::class);
        $categorie = $repository->find($id);
        if (!$categorie){
            throw $this->createNotFoundException('No categorie found for this id');
        }
        $products = [];
        
        foreach($categorie->getProducts()as $product){
            $products[]=[
                'libelle'=> $product->getLibelle(),
                'price' =>$product->getPrice(),
                'stock'=>$product->getStock(),
                'description'=>$product->getDescription()   
            ];
        }
        return $this->json($products);
    }

     /**
     * @Route("/categories/{id}", name="delete_categorie", methods={"DELETE"})
     */
    public function deleteCategorie(ManagerRegistry $doctrine,int $id,FileUploader $fileUploader): Response
    {
        $repository = $doctrine->getRepository(Categorie::class);
        $categorie = $repository->find($id);
        if (!$categorie) {
            return $this->json('No orderState found for id' . $id, 404);
        }
        $image = $categorie->getImage();
        $fileUploader->deleteImage('/categorie'.'/'.$image);

        $repository->remove($categorie);
        $repository->flush();
 
        return $this->json('Deleted an orderState successfully ');
    }


    /**
     * @Route("/categories/{id}", name="Update_categorie", methods={"PATCH"})
     */

     public function update(ManagerRegistry $doctrine,Request $request,int $id):Response
     {
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Categorie::class);
        $categorie = $repository->find($id);

        if (!$categorie){
            throw $this->createNotFoundException('No categorie found for this id');
        }

        $categorie->setLibelle($request->request->get('libelle'));
        $entityManager->persist($categorie);
        $entityManager->flush();

        return $this->json($categorie->getLibelle());
     }

}

