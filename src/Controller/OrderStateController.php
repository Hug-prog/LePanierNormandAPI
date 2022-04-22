<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\OrderState;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/api", name="api_")
 */
class OrderStateController extends AbstractController
{
    /**
     *@Route("/orderstates", name="get_orderStates", methods={"GET"})
     */
    public function getOrderStates(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(OrderState::class);
        $orderStates = $repository->findAll();

 
        $data = [];
 
        foreach ($orderStates as $orderState) {
           $data[] = [
               'id' => $orderState->getId(),
               'libelle' => $orderState->getLibelle(),
           ];
        }

 
        return $this->json($data);
    }
    /**
     * @Route("/orderstates", name="add_orderState", methods={"POST"})
     */
    public function createOrderState(ManagerRegistry $doctrine,Request $request): Response
    { 
        $entityManager = $doctrine->getManager();
        $orderState = new OrderState();
        $orderState->setLibelle($request->request->get('libelle'));

        $entityManager->persist($orderState);
        $entityManager->flush();
 
        return $this->json($orderState->getLibelle());
    }
    /**
     * @Route("/orderstates/{id}", name="delete_orderState", methods={"DELETE"})
     */
    public function deleteOrderState(ManagerRegistry $doctrine,int $id): Response
    {
        $repository = $doctrine->getRepository(OrderState::class);
        $orderState = $repository->find($id);
        if (!$orderState) {
            return $this->json('No orderState found for id' . $id, 404);
        }
        $repository->remove($orderState);
        $repository->flush();
 
        return $this->json('Deleted an orderState successfully ');
    }
    /**
     * @Route("/orderstates/{id}", name="get_orderState", methods={"GET"})
     */
    public function getOrderState(ManagerRegistry $doctrine,int $id): Response
    {
        $repository = $doctrine->getRepository(OrderState::class);
        $orderState = $repository->find($id);
        if (!$orderState) {
            return $this->json('No orderState found for id' . $id, 404);
        }
            $data[] = [
                'id' => $orderState->getId(),
                'libelle' => $orderState->getLibelle(),
            ];
        return $this->json($data);
    }
    /**
     * @Route("/orderstates/{id}", name="get_orderState", methods={"PATCH"})
     */
    public function patchOrderState(ManagerRegistry $doctrine,int $id): Response
    {
        $repository = $doctrine->getRepository(OrderState::class);
        $orderState = $repository->find($id);
        if (!$orderState) {
            return $this->json('No orderState found for id' . $id, 404);
        }
            $data[] = [
                'id' => $orderState->getId(),
                'libelle' => $orderState->getLibelle(),
            ];
        return $this->json($data);
    }
}
