<?php
namespace App\Service;


use App\Entity\Order;
use App\Entity\OrderItemMap;
use Doctrine\ORM\EntityManager;

class OrderService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * OrderService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $params
     */
    public function create(array $params): void
    {
        $order = new Order();
        $customer = $this->entityManager->getRepository('App:Customers')->find($params['customer_id']);
        $order->setCustomer($customer);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        $total = 0;
        foreach ($params['items'] as $item) {
            $product = $this->entityManager->getRepository('App:Product')->find($item['id']);
            $totalPrice = $product->getPrice() * $item['quantity'];
            $total += $totalPrice;
            $orderItemMap = new OrderItemMap();
            $orderItemMap->setOrder($order);
            $orderItemMap->setProduct($product);
            $orderItemMap->setTotal($totalPrice);
            $orderItemMap->setQuantity($item['quantity']);
            $product->setStock($product->getStock() - $item['quantity']);
            $this->entityManager->persist($orderItemMap);
        }
        $order->setTotal($total);
        $this->entityManager->flush();
    }

    /**
     * @param int $orderId
     * @return array
     */
    public function getOrderById(int $orderId): array {
        $order = $this->entityManager->getRepository('App:Order')->find($orderId);
        return $this->prepareOrderData($order);
    }

    /**
     * @return array
     */
    public function getOrders(): array {
        $orders = $this->entityManager->getRepository('App:Order')->findAll();
        $ordersResult = [];
        foreach ($orders as $order) {
            $ordersResult[] = $this->prepareOrderData($order);
        }
        return $ordersResult;
    }

    /**
     * @param Order $order
     * @return array
     */
    public function prepareOrderData(Order $order): array {
        $orderData['id'] = $order->getId();
        $orderData['customerId'] = $order->getCustomer()->getId();

        foreach ($order->getItems() as $item) {
            $item = [
                'productId' => $item->getProduct()->getId(),
                'quantity' => $item->getQuantity(),
                'unitPrice' => $item->getProduct()->getPrice(),
                'total' => $item->getTotal()
            ];
            $orderData['items'][] = $item;
        }
        $orderData['total'] = $order->getTotal();

        return $orderData;
    }
}