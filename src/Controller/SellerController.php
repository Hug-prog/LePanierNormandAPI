<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Seller;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/api", name="api_")
 */
class SellerController extends AbstractController
{
     /**
     * @Route("/sellers", name="add_seller", methods={"POST"})
     */
    public function createSeller(ManagerRegistry $doctrine,Request $request,FileUploader $fileUploader): Response
    { 
        $entityManager = $doctrine->getManager();
        $seller = new Seller();
        $seller->setLibelle($request->request->get('libelle'));
        $seller->setPostCode($request->request->get('postCode'));
        $seller->setCity($request->request->get('city'));
        $seller->setHouseNumber($request->request->get('houseNumber'));
        $seller->setStreet($request->request->get('street'));
        $file = $request->files->get('image');
        $fileName = $fileUploader->uploadImage($file,'/seller');
        $seller->setImage($fileName);

        $entityManager->persist($seller);
        $entityManager->flush();
 
        return $this->json("seller has been created");
    }
     /**
     * @Route("/sellers", name="get_sellers", methods={"GET"})
     */
    public function getSellers(ManagerRegistry $doctrine):Response
    {
        $repository = $doctrine->getRepository(Seller::class);
        $sellers = $repository->findAll();

        $data = [];
        foreach($sellers as $seller){
            $data[]=[
                'id'=>$seller->getId(),
                'libelle'=>$seller->getLibelle(),
                'postCode'=>$seller->getPostCode(),
                'city'=>$seller->getCity(),
                'houseNumber'=>$seller->getHouseNumber(),
                'street'=>$seller->getStreet(),
                'image'=>$seller->getImage()
            ];
        }
        return $this->json($data);
    }
    /**
     * @Route("/sellers/{id}", name="GET_seller", methods={"GET"})
     */
    public function getSellerById(ManagerRegistry $doctrine, int $id): Response
    {
        $repository = $doctrine->getRepository(Seller::class);
        $seller = $repository->find($id);
        if (!$seller){
            throw $this->createNotFoundException('No categorie found for this id');
        }
        return $this->json($seller->getLibelle());
    }
    /**
     * @Route("/sellers/{id}", name="delete_seller", methods={"DELETE"})
     */
    public function deleteSeler(ManagerRegistry $doctrine,int $id,FileUploader $fileUploader): Response
    {
        $repository = $doctrine->getRepository(Seller::class);
        $seller = $repository->find($id);
        if (!$seller) {
            return $this->json('No orderState found for id' . $id, 404);
        }
        $image = $seller->getImage();
        $fileUploader->deleteImage('/categorie'.'/'.$image);

        $repository->remove($seller);
        $repository->flush();
 
        return $this->json('Deleted an orderState successfully ');
    }
}
