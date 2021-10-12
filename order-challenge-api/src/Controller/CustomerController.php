<?php

namespace App\Controller;

use App\Service\CustomerService;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class CustomerController extends BaseController
{
    /**
     * @Route("/api/customers/{id}", name="customer", methods={"GET"})
     * @param Request $request
     * @param CustomerService $customerService
     * @return JsonResponse
     */
    public function customer(Request $request, CustomerService $customerService): JsonResponse
    {
        return $this->json($customerService->getCustomerById($request->get('id')), Response::HTTP_OK);
    }

    /**
     * @Route("/api/customers", name="customers", methods={"GET"})
     * @param CustomerService $customerService
     * @return JsonResponse
     */
    public function customers(CustomerService $customerService): JsonResponse
    {
        return $this->json($customerService->getCustomers(), Response::HTTP_OK);
    }

    /**
     * @Route("/api/customers", name="customers_crate", methods={"POST"})
     * @param Request $request
     * @param CustomerService $customerService
     * @return JsonResponse
     */
    public function create(Request $request, CustomerService $customerService): JsonResponse
    {
        $postData = json_decode($request->getContent(), true);
        $constraint = new Assert\Collection([
            'name' => new Assert\NotBlank(),
            'since' => new Assert\NotBlank(),
            'revenue' => new Assert\NotBlank(),
        ]);

        if ($message = $this->validate($postData, $constraint)) {
            return $this->json(['message' => $message], Response::HTTP_BAD_REQUEST);
        }

        $customerService->create($postData);
        return $this->json(['message' => 'Customer Created'], Response::HTTP_CREATED);
    }
}
