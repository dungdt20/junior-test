<?php
namespace App\Services;

use App\Helpers\PriceItemCalculate;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\OrdersToProductsRepositoryInterface;
use App\Systems\Database\RedisConnector;
use RedisException;

class OrderService extends ServiceAbstract
{
    protected \Redis $redis;

    protected OrderRepositoryInterface $orderRepository;

    protected OrdersToProductsRepositoryInterface $ordersToProductsRepository;

    public function __construct(
        \Redis $redis,
        OrderRepositoryInterface $orderRepository,
        OrdersToProductsRepositoryInterface $ordersToProductsRepository
    )
    {
        $this->redis = $redis;
        $this->orderRepository = $orderRepository;
        $this->ordersToProductsRepository = $ordersToProductsRepository;
    }

    public function getAll(): array
    {
        $orders = $this->orderRepository->findAll();

        $orderIds = array_map(fn ($order) => $order['id'], $orders);

        $orderProducts = $this->ordersToProductsRepository->findAllProductsByOrderIds($orderIds);

        $productsByOrderId = array();
        foreach ($orderProducts as $product) {
            $productsByOrderId[$product['order_id']][] = $product;
        }

        $orders = array_map(function ($order) use ($productsByOrderId) {
            $products = $productsByOrderId[$order['id']];
            $order['price'] = PriceItemCalculate::calculateAll($products, $order);
            $order['products'] = $products;
            return $order;
        }, $orders);

        return $this->returnSuccessResponse($orders);
    }

    /**
     * @throws RedisException
     */
    public function getDetail($id): array
    {
        $redisData = $this->redis->get((string) $id);
        if ($redisData) {
            $result = array_values(json_decode($redisData, true));
            return $this->returnSuccessResponse($result);
        }

        $resultDB = $this->getDetailFromDB($id);

        // expire 1h = 60 * 60
        $this->redis->setex((string) $id, 60 * 60, json_encode($resultDB));

        return $this->returnSuccessResponse($resultDB);
    }

    public function getDetailFromDB($id)
    {
        $result = $this->orderRepository->find($id);
        if (! $result) {
            return $result;
        }
        $orderProducts = $this->ordersToProductsRepository->findAllProductsByOrderIds(array($id));

        $result = $result[0];
        $result['price'] = PriceItemCalculate::calculateAll($orderProducts, $result);
        $result['products'] = $orderProducts;

        return $result;
    }

    public function create(array $input): array
    {
        $productIds = $input['product_ids'];
        unset($input['product_ids']);

        $orderInsertedId = $this->orderRepository->insert($input);

        foreach ($productIds as $productId) {
            $this->ordersToProductsRepository->insert(array(
                'order_id' => $orderInsertedId,
                'product_id' => (int) $productId,
            ));
        }
        $input['order_id'] = $orderInsertedId;

        return $this->returnCreatedResponse($input);
    }
}
