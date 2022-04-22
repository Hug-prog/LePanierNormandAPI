<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users", name="add_user", methods={"POST"})
     */
    public function createUser(ManagerRegistry $doctrine,Request $request): Response
    { 
        $entityManager = $doctrine->getManager();
        $user = new User();
        $user->setMail($request->request->get('mail'));
        


        $entityManager->persist($user);
        $entityManager->flush();
 
        return $this->json($user);
    }
}
