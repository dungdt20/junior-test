<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepositoryImplement implements ProductRepositoryInterface
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                id, name, amazon_price, product_weight, product_width, product_height, product_depth
            FROM
                products;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT 
                id, name, amazon_price, product_weight, product_width, product_height, product_depth
            FROM
                products
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

    public function insert(Array $input): int
    {
        $statement = "
            INSERT INTO products
                (name, amazon_price, product_weight, product_width, product_height, product_depth)
            VALUES
                (:name, :amazon_price, :product_weight, :product_width, :product_height, :product_depth);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'name' => $input['name'],
                'amazon_price'  => $input['amazon_price'],
                'product_weight' => $input['product_weight'],
                'product_width' => $input['product_width'],
                'product_height' => $input['product_height'],
                'product_depth' => $input['product_depth'],
            ));
            return (int) $this->db->lastInsertId();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE products
            SET 
                name = :name,
                amazon_price  = :amazon_price,
                product_weight = :product_weight,
                product_width = :product_width,
                product_height = :product_height,
                product_depth = :product_depth,
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'name' => $input['name'],
                'amazon_price'  => $input['amazon_price'],
                'product_weight' => $input['product_weight'],
                'product_width' => $input['product_width'],
                'product_height' => $input['product_height'],
                'product_depth' => $input['product_depth'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
