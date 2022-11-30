<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\OrdersToProductsRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class OrdersToProductsRepositoryImplement implements OrdersToProductsRepositoryInterface
{

    protected $db;

    protected OrderRepositoryInterface $orderRepository;

    protected ProductRepositoryInterface $productRepository;

    public function __construct($db)
    {
        $this->db = $db;
        $this->orderRepository = new OrderRepositoryImplement($db);
        $this->productRepository = new ProductRepositoryImplement($db);
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                id, order_id, product_id
            FROM
                orders_to_products;
        ";

        try {
            $statement = $this->db->query($statement);
            $resultStatement = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $resultStatement;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT 
                id, order_id, product_id
            FROM
                orders_to_products
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findAllProductsByOrderIds(array $orderIds): array
    {
        $place_holders = implode(',', array_fill(0, count($orderIds), '?'));

        $statement = "
            SELECT 
                o.id, o.order_id, o.product_id, p.amazon_price, p.product_weight,
                p.product_width, p.product_height, p.product_depth
            FROM
                (
                    SELECT id, order_id, product_id
                    FROM   orders_to_products
                    WHERE order_id IN ($place_holders)
                )  o JOIN products p ON o.product_id = p.id 
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute($orderIds);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input): int
    {
        if (empty($this->orderRepository->find($input['order_id']))) {
            return 0;
        }
        if (empty($this->productRepository->find($input['product_id']))) {
            return 0;
        }

        $statement = "
            INSERT INTO orders_to_products
                (order_id, product_id)
            VALUES
                (:order_id, :product_id);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'order_id'  => $input['order_id'],
                'product_id' => $input['product_id'],
            ));
            return (int) $this->db->lastInsertId();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE orders
            SET 
                order_id = :order_id,
                product_id  = :product_id,
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'order_id' => $input['order_id'],
                'product_id'  => $input['product_id'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
