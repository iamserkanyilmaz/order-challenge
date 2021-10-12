<?php
namespace App\Service\Discount;

use App\Entity\Discount;
use App\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;


class DiscountService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    private const  APPLY_TYPE_CHEAPEST_PRODUCT = 'cheapest_product';
    private const  APPLY_TYPE_FULL_AMOUNT = 'full_amount';

    /**
     * DiscountService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $orderId
     * @return array
     */
    public function check(int $orderId): array {
        $order = $this->entityManager->getRepository('App:Order')->find($orderId);
        $discounts = $this->entityManager->getRepository('App:Discount')->findAll();
        $rules = $this->defaultValidators();

        $result = [
            'orderId' => $order->getId(),
        ];

        $calculateDiscounts = [];
        foreach ($discounts as $discount) {
            $validator = new ValidateService($order, $discount);
            if ($validator->run($rules)){
                $calculateDiscounts[] = $this->calculate($order, $discount);
            }
        }

        $totalDiscount = 0;
        foreach ($calculateDiscounts as $discontedVal) {
            $totalDiscount += $discontedVal['discountAmount'];
        }

        $sort = new Criteria(null, ['subTotal' => Criteria::ASC]);
        $collection = new ArrayCollection($calculateDiscounts);
        $subTotalSort = $collection->matching($sort)->toArray();
        $subTotalSort = reset($subTotalSort);
        $result['discounts'] = $calculateDiscounts;
        $result['totalDiscount'] = $totalDiscount;
        $result['discountedTotal'] = ($subTotalSort['subTotal'] ? $subTotalSort['subTotal'] : 0);

        return $result;
    }

    /**
     * @param Order $order
     * @param Discount $discount
     * @return array
     */
    public function calculate(Order $order, Discount $discount): array
    {
        $discountAmount = $this->calculateDiscountForFullTotalAmount($order, $discount, $discount->getDiscountAmount());

        if ((!is_null($discount->getCategoryId())
            && !is_null($discount->getQuantityPurchased())
            && !is_null($discount->getFreeCount()))) {
            $discountAmount = $this->calculateDiscountForFreeProduct($order, $discount, $discountAmount);
        }

        if ((!is_null($discount->getCategoryId())
            && !is_null($discount->getQuantityPurchased())
            && $discount->getApplyType() == self::APPLY_TYPE_CHEAPEST_PRODUCT))
        {
            $discountAmount = $this->calculateDiscountForCheapestProduct($order, $discount, $discountAmount);
        }

        $subTotal = $order->getTotal() - $discountAmount;

        return [
            'discountReason' => $discount->getReason(),
            'discountAmount' => $discountAmount,
            'subTotal' => $subTotal
        ];
    }

    /**
     * @param Order $order
     * @param Discount $discount
     * @param float|null $discountAmount
     * @return float|null
     */
    private function calculateDiscountForFullTotalAmount(Order $order, Discount $discount, ?float $discountAmount): ?float
    {
        if ($discount->getDiscountAmount() &&
            $discount->getDiscountAmountType() == 'percent' &&
            $discount->getApplyType() == self::APPLY_TYPE_FULL_AMOUNT
        ){
            $discountAmount = ($order->getTotal() / 100) * $discountAmount;
        }

        return $discountAmount;
    }

    /**
     * @param Order $order
     * @param Discount $discount
     * @param float|null $discountAmount
     * @return float|null
     */
    private function calculateDiscountForFreeProduct(Order $order, Discount $discount, ?float $discountAmount): ?float
    {
        foreach ($order->getItems() as $item) {

            if ($item->getProduct()->getCategory() == $discount->getCategoryId() &&
                $item->getQuantity() >= $discount->getQuantityPurchased()) {
                $discountAmount = $item->getProduct()->getPrice() * $discount->getFreeCount();
            }
        }

        return $discountAmount;
    }

    /**
     * @param Order $order
     * @param Discount $discount
     * @param float|null $discountAmount
     * @return float|null
     */
    private function calculateDiscountForCheapestProduct(Order $order, Discount $discount, ?float $discountAmount) : ?float
    {
        $itemsArray = [];
        foreach ($order->getItems() as $item) {
            $priceArr = [
                'product_id' => $item->getProduct()->getId(),
                'price' => $item->getProduct()->getPrice()
            ];
            $itemsArray[] = $priceArr;
        }
        $sort = new Criteria(null, ['price' => Criteria::DESC]);
        $collection = new ArrayCollection($itemsArray);
        $list = $collection->matching($sort)->toArray();
        $product = reset($list);

        foreach ($order->getItems() as $item) {
            if ($item->getProduct()->getId() == $product['product_id'] &&
                $discount->getDiscountAmountType() == 'percent') {
                $discountAmount = ($item->getTotal() / 100) * $discountAmount;
            }
        }

        return $discountAmount;
    }

    /**
     * @return array
     */
    private function defaultValidators(): array
    {
        return [
            new Validators\TotalAmountValidator($this->entityManager),
            new Validators\CategoryAndProductCountValidator($this->entityManager),
        ];
    }

}