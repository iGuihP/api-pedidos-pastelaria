<?php

namespace App\Services\Order;

use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\ProductsOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Snowfire\Beautymail\Beautymail;

class CreateOrderService
{
    private $orderRepository;
    private $productsOrderRepository;
    private $customerRepository;
    private $productRepository;
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductsOrderRepositoryInterface $productsOrderRepository,
        CustomerRepositoryInterface $customerRepository,
        ProductRepository $productRepository,
    ) {
        $this->orderRepository = $orderRepository;
        $this->productsOrderRepository = $productsOrderRepository;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
    }

    public function create(array $params): int {
        Log::info("Running the service to create a new product order.", $params);

        $customerFound = $this->getCustomerDetails($params['customerId']);
        $this->findProducts($params['productsId']);
        $createdOrder = $this->createOrder($params['customerId']);
        $productsOrderFormatted = $this->formatProductsOrderData($createdOrder->id, $params['productsId']);
        $this->createProductOrder($productsOrderFormatted);
        $this->sendMail($customerFound->email, $customerFound->name, $createdOrder->id);

        return $createdOrder->id;
    }

    private function getCustomerDetails(int $custoemrId) {
        $customer = $this->customerRepository->findById($custoemrId);
        if(!$customer) {
            throw new Exception("Customer not found.", 404);
        }
        return $customer;
    }

    private function findProducts(array $productsId) {
        $productsFound = $this->productRepository->findByIds($productsId);
        if(!$productsFound || count($productsFound) !== count($productsId)) {
            throw new Exception("Same product not found.", 404);
        }
    }

    private function createOrder(int $customerId) {
        return $this->orderRepository->create($customerId);
    }

    private function formatProductsOrderData(int $orderId, array $productsId) {
        $formattedData = [];

        foreach ($productsId as $productId) {
            $formattedData[] = [
                'product_id' => $productId,
                'order_id' => $orderId
            ];
        }

        return $formattedData;
    } 

    private function createProductOrder(array $productsOrder) {
        $createdProductOrder = $this->productsOrderRepository->create($productsOrder);

        if(!$createdProductOrder) {
            throw new Exception("Failed to create product order.", 500);
        }

        return $createdProductOrder;
    }

    private function sendMail(string $email, string $name, int $orderId) {
        $beautymail = app()->make(Beautymail::class);
        $beautymail->send('emails.order', [], function($message) use($email, $name, $orderId)
        {
            $message
                ->from(env('MAIL_USERNAME'), 'The Pastry Palace')
                ->to($email, $name)
                ->subject('PEDIDO #'.$orderId.' GERADO');
        });
    }
}
