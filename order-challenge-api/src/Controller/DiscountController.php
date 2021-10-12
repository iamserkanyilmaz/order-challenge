<?php

namespace App\Controller;

use App\Service\Discount\DiscountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class DiscountController extends BaseController
{
    /**
     * @Route("/api/discounts", name="discount")
     * @param Request $request
     * @param DiscountService $discountService
     * @return Response
     */
    public function index(Request $request, DiscountService $discountService): JsonResponse
    {
        $postData = json_decode($request->getContent(), true);
        $constraint = new Assert\Collection([
            'order_id' => new Assert\NotBlank(),
        ]);

        if ($message = $this->validate($postData, $constraint)) {
            return $this->json(['message' => $message], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($discountService->check(intval($postData['order_id'])), Response::HTTP_OK);
    }
}
