<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Product;
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
        $user->setEmail($request->request->get('email'));
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
     * @Route("/user", name="get_user", methods={"GET"})
     */
    public function getCurrentUser(): Response
    {
        $user=$this->getUser();
        $likedProducts = [];
        foreach($user->getLikedProducts() as $likedProduct){
            $likedProducts[]=$likedProduct->getId();
        }
        $queryData = [
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'houseNumber' => $user->getHouseNumber(),
            'street' => $user->getStreet(),
            'postCode' => $user->getPostCode(),
            'city' => $user->getCity(),
            'likedProducts' => $likedProducts,
        ];
        return $this->json($queryData);
    }

    /**
     * @Route("/user/likedproducts", name="get_user_liked_products", methods={"GET"})
     */
    public function getUserLikedProducts(): Response
    {
        $user=$this->getUser();
        $likedProducts=[];
        foreach($user->getLikedProducts() as $product){
            $likedProducts[]=[
                'id' => $product->getId(),
               'libelle' => $product->getlibelle(),
               'price' => $product->getPrice(),
               'stock'=> $product->getStock(),
               'description' => $product->getDescription(),
               'seller' => $product->getProductSel()->getId(),
               'images' => $product->getImages()
            ];
        }
        return $this->json($likedProducts);
    }
    /**
     * @Route("/user/like/product/{id}", methods={"patch"})
     */
    public function likeProduct(ManagerRegistry $doctrine,int $id){
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->find($id);
        $user = $this->getUser()->addLikedProduct($product);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json('ajouté à la wishlist');
    }
    /**
     * @Route("/user/unlike/product/{id}", methods={"patch"})
     */
    public function unlikeProduct(ManagerRegistry $doctrine,int $id){
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->find($id);
        $user = $this->getUser()->removeLikedProduct($product);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json('retiré à la wishlist');
    }
}