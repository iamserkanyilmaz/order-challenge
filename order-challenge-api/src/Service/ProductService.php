<?php
namespace App\Service;


use App\Entity\Order;
use App\Entity\OrderItemMap;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;

class ProductService
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(array $params): void
    {
        $product = new Product();
        $product->setName($params['name']);
        $product->setCategory($params['category_id']);
        $product->setPrice($params['price']);
        $product->setStock($params['stock']);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    /**
     * @param int $productId
     * @param int $stock
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateStock(int $productId, int $stock): void
    {
        $product = $this->entityManager->getRepository('App:Product')->find($productId);
        $product->setStock($stock);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    /**
     * @param int $productId
     * @return array
     */
    public function getProductById(int $productId): array {
        $product = $this->entityManager->getRepository('App:Product')->find($productId);
        return $this->prepareProductData($product);
    }

    /**
     * @return array
     */
    public function getProducts(): array {
        $products = $this->entityManager->getRepository('App:Product')->findAll();
        $productsResult = [];
        foreach ($products as $product) {
            $productsResult[] = $this->prepareProductData($product);
        }
        return $productsResult;
    }

    /**
     * @param Product $product
     * @return array
     */
    public function prepareProductData(Product $product): array {
        return [
            'name' => $product->getName(),
            'category' => $product->getCategory(),
            'price' => $product->getPrice(),
            'stock' => $product->getStock()
        ];
    }
}