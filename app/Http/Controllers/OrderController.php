<?php

namespace App\Http\Controllers;

use App\Services\Order\UpdateOrderService;
use App\Services\Order\FindOrderByCustomerIdService;
use App\Services\Order\FindOrderByIdService;
use App\Repositories\OrderRepository;
use App\Repositories\ProductsOrderRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function findById($id) {
        try {
            $orderRepository = new OrderRepository();
            $findOrdersByIdService = new FindOrderByIdService($orderRepository);
            $ordersFound = $findOrdersByIdService->find((int) $id);

            return response()->json($ordersFound, 200);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to find orders by ID. Location: OrderController::findById', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function findByCustomerId($id) {
        try {
            $orderRepository = new OrderRepository();
            $findOrdersByCustomerIdService = new FindOrderByCustomerIdService($orderRepository);
            $ordersFound = $findOrdersByCustomerIdService->find((int) $id);

            return response()->json($ordersFound, 200);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to find orders by Customer ID. Location: OrderController::findByCustomerId', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function update(Request $request, $id) {
        $params = $request->all();
        try {
            $this->validateRequestParameters([
                'products_id' => 'array|required'
            ], $params);

            $orderRepository = new OrderRepository();
            $productsOrderRepository = new ProductsOrderRepository();
            $updateOrderService = new UpdateOrderService($orderRepository, $productsOrderRepository);
            $updateOrderService->update((int) $id, $params['products_id']);

            return response()->json([], 200);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to find orders by Customer ID. Location: OrderController::findByCustomerId', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }
}
