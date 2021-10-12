<?php
namespace App\Service;


use App\Entity\Customer;
use Doctrine\ORM\EntityManager;

class CustomerService
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
        $customer = new Customer();
        $customer->setName($params['name']);
        $customer->setSince(new \DateTime($params['since']));
        $customer->setRevenue($params['revenue']);
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }

    /**
     * @param int $customerId
     * @return array
     */
    public function getCustomerById(int $customerId): array {
        $customer = $this->entityManager->getRepository('App:Customer')->find($customerId);
        return $this->prepareCustomerData($customer);
    }

    /**
     * @return array
     */
    public function getCustomers(): array {
        $customers = $this->entityManager->getRepository('App:Customer')->findAll();
        $customersResult = [];
        foreach ($customers as $customer) {
            $customersResult[] = $this->prepareCustomerData($customer);
        }
        return $customersResult;
    }

    /**
     * @param Customer $customer
     * @return array
     */
    public function prepareCustomerData(Customer $customer): array {

        return [
            'id'=> $customer->getId(),
            'name' => $customer->getName(),
            'since' => $customer->getSince()->format('Y-m-d'),
            'revenue' => $customer->getRevenue()
        ];
    }
}