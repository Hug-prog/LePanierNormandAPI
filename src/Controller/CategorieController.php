<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categorie;

/**
 * @Route("/api", name="api_")
 */

class CategorieController extends AbstractController
{
   /**
    * @Route('/categorie',name='app_categorie)
    */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CategorieController.php',
        ]);
    }
    /**
     * @Route("/categories", name="add_categorie", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $dd($request);
        $entityManager = $this->getDoctrine()->getManager();
 
        $categorie = new Categorie();
        $categorie->setLibelle($request->request->get('libelle'));
 
        $entityManager->persist($categorie);
        $entityManager->flush();
 
        return $this->json('Created new categorie ' . $categorie->getId());
    }
}

