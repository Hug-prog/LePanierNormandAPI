<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @Route("/api", name="api_")
 */

class CategorieController extends AbstractController
{

    /**
     * @Route("/categories", name="add_categorie", methods={"POST"})
     */
    public function createCategorie(ManagerRegistry $doctrine,Request $request): Response
    { 
        $entityManager = $doctrine->getManager();
        $categorie = new Categorie();
        $categorie->setLibelle($request->request->get('libelle'));

        $entityManager->persist($categorie);
        $entityManager->flush();
 
        return $this->json($categorie->getLibelle());
    }
}

