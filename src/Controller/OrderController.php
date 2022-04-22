<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
use App\Entity\OrderState;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use \DateTimeImmutable;

/**
 * @Route("/api", name="api_")
 */
class OrderController extends AbstractController
{
     /**
     *@Route("/orders", name="get_orders", methods={"GET"})
     */
    public function getOrders(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Order::class);
        $orders = $repository->findAll();

 
        $data = [];
 
        foreach ($orders as $order) {
           $data[] = [
               'id' => $order->getId(),
               'createdAt' => $order->getCreatedAt(),
               'totalPrice' => $order->getTotalPrice(),
               'state' => $order->getState()->getLibelle(),
           ];
        }

 
        return $this->json($data);
    }
    /**
     * @Route("/orders", name="add_order", methods={"POST"})
     */
    public function createOrder(ManagerRegistry $doctrine,Request $request): Response
    { 
        $entityManager = $doctrine->getManager();
        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $state = $doctrine->getRepository(OrderState::class)->find($request->request->get('stateId')); 
        $order->setState($state);
        $order->setTotalPrice($request->request->get('totalPrice'));
        $order->setUser($request->request->get('userId'));
        $state = $doctrine->getRepository(User::class)->find($request->request->get('userId'));

        $entityManager->persist($order);
        $entityManager->flush();
 
        return $this->json($order);
    }
    /**
     * @Route("/orders/{id}", name="delete_order", methods={"DELETE"})
     */
    public function deleteOrder(ManagerRegistry $doctrine,int $id): Response
    {
        $repository = $doctrine->getRepository(Order::class);
        $order = $repository->find($id);
        if (!$order) {
            return $this->json('No order found for id' . $id, 404);
        }
        $repository->remove($order);
        $repository->flush();
 
        return $this->json('Deleted an order successfully ');
    }
    /**
     * @Route("/orders/{id}", name="get_order", methods={"GET"})
     */
    public function getOrder(ManagerRegistry $doctrine,int $id): Response
    {
        $repository = $doctrine->getRepository(Order::class);
        $order = $repository->find($id);
        if (!$order) {
            return $this->json('No order found for id' . $id, 404);
        }
            $data[] = [
                'id' => $order->getId(),
                'libelle' => $order->getLibelle(),
            ];
        return $this->json($data);
    }
    /**
     * @Route("/orders/{id}", name="get_order", methods={"PATCH"})
     */
    public function patchOrder(ManagerRegistry $doctrine,int $id): Response
    {
        $repository = $doctrine->getRepository(Order::class);
        $order = $repository->find($id);
        if (!$order) {
            return $this->json('No order found for id' . $id, 404);
        }
            $data[] = [
                'id' => $order->getId(),
                'libelle' => $order->getLibelle(),
            ];
        return $this->json($data);
    }
}
