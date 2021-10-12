<?php

namespace App\Controller;

use App\Service\OrderService;
use App\Validator\Constraints\ConstraintItems;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class OrderController extends BaseController
{
    /**
     * @Route("/api/orders/{id}", name="orders", methods={"GET"})
     * @param Request $request
     * @param OrderService $orderService
     * @return JsonResponse
     */
    public function order(Request $request, OrderService $orderService): JsonResponse
    {
        return $this->json($orderService->getOrderById($request->get('id')), Response::HTTP_OK);
    }

    /**
     * @Route("/api/orders", name="orders_", methods={"GET"})
     * @param OrderService $orderService
     * @return JsonResponse
     */
    public function orders(OrderService $orderService): JsonResponse
    {

        return $this->json($orderService->getOrders(), Response::HTTP_OK);
    }

    /**
     * @Route("/api/orders", name="orders_crate", methods={"POST"})
     * @param Request $request
     * @param OrderService $orderService
     * @return JsonResponse
     */
    public function create(Request $request, OrderService $orderService): JsonResponse
    {
        $postData = json_decode($request->getContent(), true);
        $em = $this->get('doctrine');
        $constraint = new Assert\Collection([
            'customer_id' => new Assert\NotBlank(),
            'items' => new ConstraintItems([
                    'entityManager' => $em
                ]
            )
        ]);

        if ($message = $this->validate($postData, $constraint)) {
            return $this->json(['message' => $message], Response::HTTP_BAD_REQUEST);
        }

        $orderService->create($postData);
        return $this->json(['message' => 'Order Created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/orders/{id}", name="orders_crate", methods={"DELETE"})
     * @param Request $request
     * @param OrderService $orderService
     * @return JsonResponse
     */
    public function delete(Request $request, OrderService $orderService): JsonResponse
    {
        $orderService->deleteOrderById($request->get('id'));
        return $this->json(['message' => 'Order Deleted'], Response::HTTP_OK);
    }
}
