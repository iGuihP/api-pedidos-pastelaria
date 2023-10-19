<?php

namespace App\Services\Order;

use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductsOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateOrderService
{
    private $orderRepository;
    private $productsOrderRepository;
    private $customerRepository;
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductsOrderRepositoryInterface $productsOrderRepository,
        CustomerRepositoryInterface $customerRepository

    ) {
        $this->orderRepository = $orderRepository;
        $this->productsOrderRepository = $productsOrderRepository;
        $this->customerRepository = $customerRepository;
    }

    public function create(array $params): int {
        Log::info("Running the service to create a new product order.", $params);

        $customerFound = $this->getCustomerDetails($params['customerId']);
        $createdOrder = $this->createOrder($params['customerId']);
        $productsOrderFormatted = $this->formatProductsOrderData($createdOrder->id, $params['productsId']);
        $this->createProductOrder($productsOrderFormatted);
        $this->sendMail($customerFound->email, $customerFound->name, $createdOrder->id);

        return $createdOrder->id;
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

    private function createOrder(int $customerId) {
        return $this->orderRepository->create($customerId);
    }

    private function createProductOrder(array $productsOrder) {
        $createdProduct = $this->productsOrderRepository->create($productsOrder);

        if(!$createdProduct) {
            throw new Exception("Houve uma falha ao inserir produtos no pedido.", 500);
        }

        return $createdProduct;
    }

    private function getCustomerDetails(int $custoemrId) {
        $customer = $this->customerRepository->findById($custoemrId);
        if(!$customer) {
            throw new Exception("Customer not found.", 404);
        }
        return $customer;
    }

    private function sendMail(string $email, string $name, int $orderId) {
        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('emails.order', [], function($message) use($email, $name, $orderId)
        {
            $message
                ->from(env('MAIL_USERNAME'), 'The Pastry Palace')
                ->to($email, $name)
                ->subject('PEDIDO #'.$orderId.' GERADO');
        });
    }
}
