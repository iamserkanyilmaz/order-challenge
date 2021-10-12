<?php

namespace App\Controller;

use App\Service\CustomerService;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class ProductController extends BaseController
{
    /**
     * @Route("/api/products/{id}", name="product", methods={"GET"})
     * @param Request $request
     * @param ProductService $productService
     * @return JsonResponse
     */
    public function customer(Request $request, ProductService $productService): JsonResponse
    {
        return $this->json($productService->getProductById($request->get('id')), Response::HTTP_OK);
    }

    /**
     * @Route("/api/products", name="products", methods={"GET"})
     * @param ProductService $productService
     * @return JsonResponse
     */
    public function customers(ProductService $productService): JsonResponse
    {
        return $this->json($productService->getProducts(), Response::HTTP_OK);
    }

    /**
     * @Route("/api/products", name="customers_crate", methods={"POST"})
     * @param Request $request
     * @param ProductService $productService
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Request $request, ProductService $productService): JsonResponse
    {
        $postData = json_decode($request->getContent(), true);
        $constraint = new Assert\Collection([
            'name' => new Assert\NotBlank(),
            'category_id' => new Assert\NotBlank(),
            'price' => new Assert\NotBlank(),
            'stock' => new Assert\NotBlank()
        ]);

        if ($message = $this->validate($postData, $constraint)) {
            return $this->json(['message' => $message], Response::HTTP_BAD_REQUEST);
        }

        $productService->create($postData);
        return $this->json(['message' => 'Customer Created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/products/{id}", name="customers_update", methods={"PUT"})
     * @param Request $request
     * @param ProductService $productService
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateStock(Request $request, ProductService $productService): JsonResponse
    {
        $postData = json_decode($request->getContent(), true);
        $constraint = new Assert\Collection([
            'stock' => new Assert\NotBlank()
        ]);

        if ($message = $this->validate($postData, $constraint)) {
            return $this->json(['message' => $message], Response::HTTP_BAD_REQUEST);
        }

        $productService->updateStock($request->get('id'), $postData['stock']);
        return $this->json(['message' => 'Product Updated'], Response::HTTP_OK);
    }
}
