<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\OrderRepositoryImplement;
use App\Repositories\OrdersToProductsRepositoryImplement;
use App\Services\OrderService;
use App\Systems\Database\RedisConnector;

class OrderController extends ControllerAbstract
{
    protected OrderService $orderService;

    public function __construct($db, $requestMethod, $id)
    {
        parent::__construct($requestMethod, $id);

        $this->orderService = new OrderService(
            (new RedisConnector())->getRedisConnection(),
            new OrderRepositoryImplement($db),
            new OrdersToProductsRepositoryImplement($db)
        );
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->id) {
                    $response = $this->orderService->getDetail($this->id);
                } else {
                    $response = $this->orderService->getAll();
                };
                break;
            case 'POST':
                    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
                    if (! $this->validateOrder($input)) {
                        $response = $this->unprocessableEntityResponse();
                        break;
                    }
                    $response = $this->orderService->create($input);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        if (! $response) {
            $response = $this->notFoundResponse();
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function validateOrder($input): bool
    {
        if (! isset($input['user_id'])) {
            return false;
        }
        if (! isset($input['product_ids'])) {
            return false;
        }
        if (! isset($input['weight_coefficient'])) {
            return false;
        }
        if (! isset($input['dimension_coefficient'])) {
            return false;
        }
        return true;
    }
}
