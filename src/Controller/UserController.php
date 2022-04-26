<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;




/**
 * @Route("/api", name="api_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users", name="add_user", methods={"POST"})
     */
    public function createUser(UserPasswordHasherInterface $passwordHasher,ManagerRegistry $doctrine,Request $request): Response
    { 
        $entityManager = $doctrine->getManager();
        $user = new User();
        $user->setEmail($request->request->get('Email'));
        $user->setPassword($passwordHasher->hashPassword($user,$request->request->get('password')));
        $user->setFirstname($request->request->get('firstname'));
        $user->setLastname($request->request->get('lastname'));
        $user->setPostCode($request->request->get('postCode'));
        $user->setCity($request->request->get('city'));
        $user->setHouseNumber($request->request->get('houseNumber'));
        $user->setStreet($request->request->get('street'));

        $entityManager->persist($user);
        $entityManager->flush();
 
        return $this->json("user has been created");
    }
    /**
     * @Route("/login", name="login_user", methods={"POST"})
     */
    public function loginUser(UserPasswordHasherInterface $passwordHasher,ManagerRegistry $doctrine,Request $request): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $user = $repository->findOneBy([
            'email'=>$request->get('email'),
    ]);
    if (!$user || !$passwordHasher->isPasswordValid($user, $request->get('password'))) {
            return $this->json([
                'message' => 'email or password is wrong.',
            ]);
    }
    return $this->json([
        'message' => 'success!',
        //'token' => sprintf('Bearer %s', $jwt),
    ]);
    }
}