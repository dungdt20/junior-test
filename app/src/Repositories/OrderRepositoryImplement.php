<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepositoryImplement implements OrderRepositoryInterface
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
                id, user_id, weight_coefficient, dimension_coefficient
            FROM
                orders;
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
                id, user_id, weight_coefficient, dimension_coefficient
            FROM
                orders
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
            INSERT INTO orders
                (user_id, weight_coefficient, dimension_coefficient)
            VALUES
                (:user_id, :weight_coefficient, :dimension_coefficient);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'user_id' => $input['user_id'],
                'weight_coefficient'  => $input['weight_coefficient'],
                'dimension_coefficient' => $input['dimension_coefficient'],
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
                user_id = :user_id,
                weight_coefficient  = :weight_coefficient,
                dimension_coefficient = :dimension_coefficient,
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'user_id' => $input['user_id'],
                'weight_coefficient'  => $input['weight_coefficient'],
                'dimension_coefficient' => $input['dimension_coefficient'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
