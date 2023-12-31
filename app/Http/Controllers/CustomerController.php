<?php

namespace App\Http\Controllers;

use App\Services\Customer\CreateCustomerService;
use App\Services\Customer\DeleteCustomerService;
use App\Services\Customer\FindCustomerByIdService;
use App\Services\Customer\FindCustomerService;
use App\Services\Customer\ListAllCustomersService;
use App\Services\Customer\UpdateCustomerService;
use App\Repositories\CustomerRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function create(Request $request) {
        $params = $request->all();

        try {
            $this->validateRequestParameters(
                [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:customers',
                    'telephone' => 'required|string|min:10|max:11',
                    'birth' => 'required|date',
                    'address' => 'required|string',
                    'complement' => 'required|string',
                    'neighborhood' => 'required|string',
                    'zipcode' => 'required|string|min:8|max:8'
                ],
                $params
            );

            $customerRepository = new CustomerRepository();
            $createCustomerService = new CreateCustomerService($customerRepository);
            $createdCustomerId = $createCustomerService->create($params);

            return response()->json([
                'newCustomerId' => $createdCustomerId
            ], 201);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to create a new customer. Location: CustomerController::create', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function findByFilters(Request $request) {
        $params = $request->all();

        try {
            $this->validateRequestParameters(
                [
                    'name' => 'required_without_all:email|string',
                    'email' => 'required_without_all:name|email',
                ],
                $params
            );

            $customerRepository = new CustomerRepository();
            $findCustomerService = new FindCustomerService($customerRepository);
            $customersFound = $findCustomerService->find($params);

            return response()->json([
                'data' => $customersFound
            ], 200);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to find customers by filters. Location: CustomerController::findByFilters', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function findById($id) {
        try {
            $customerRepository = new CustomerRepository();
            $findCustomerByIdService = new FindCustomerByIdService($customerRepository);
            $customersFound = $findCustomerByIdService->find((int) $id);

            return response()->json($customersFound, 200);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to find customers by ID. Location: CustomerController::findById', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function listAll() {
        try {
            $customerRepository = new CustomerRepository();
            $listAllCustomersService = new ListAllCustomersService($customerRepository);
            $customersFound = $listAllCustomersService->list();

            return response()->json([
                'data' => $customersFound
            ], 200);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to list all customers. Location: CustomerController::listAll', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function update($id, Request $request) {
        $params = $request->all();
        try {
            $this->validateRequestParameters(
                [
                    'name' => 'string',
                    'email' => 'email',
                    'telephone' => 'string|min:10|max:11',
                    'birth' => 'date',
                    'address' => 'string',
                    'complement' => 'string',
                    'neighborhood' => 'string',
                    'zipcode' => 'string|min:8|max:8'
                ],
                $params
            );

            $customerRepository = new CustomerRepository();
            $updateCustomerService = new UpdateCustomerService($customerRepository);
            $updateCustomerService->update($id, $params);
            
            return response(null, 204);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to update a customer. Location: CustomerController::update', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }

    public function delete($id) {
        try {
            $customerRepository = new CustomerRepository();
            $deleteCustomerService = new DeleteCustomerService($customerRepository);
            $deleteCustomerService->delete($id);
            
            return response(null, 204);
        } catch (Exception $exception) {
            $messagesError = $this->getMessageException($exception);

            Log::error('Failed to delete a customer. Location: CustomerController::delete', $messagesError);
            return response()->json($messagesError, $this->getHttpCode($exception));
        }
    }
}
