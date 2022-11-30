<?php
require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS products (
        id int not null auto_increment,
        name varchar(100) not null,
        amazon_price float8 not null,
        product_weight float8 not null,
        product_width float8 not null,
        product_height float8 not null,
        product_depth float8 not null,
        primary key (id)
    ) engine = INNODB;

    CREATE TABLE IF NOT EXISTS orders (
        id int not null auto_increment,
        user_id int not null,
        weight_coefficient float8 not null,
        dimension_coefficient float8 not null,
        primary key (id)
    ) engine = INNNODB;

    CREATE TABLE IF NOT EXISTS orders_to_products (
        id int not null auto_increment,
        order_id int,
        product_id int,
        primary key (id),
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
    ) engine = INNNODB;

    INSERT INTO products
        (id, name, amazon_price, product_weight, product_width, product_height, product_depth)
    VALUES
        (1, 'Toy', 100, 100, 100, 100, 100),
        (2, 'Pen', 200, 20, 20, 20, 20),
        (3, 'Pencil', 100, 30, 40, 50, 20),
        (4, 'Laptop', 400, 700, 100, 500, 200),
        (5, 'Desk', 400, 500, 500, 500, 500),
        (6, 'Mouse', 100, 10, 10, 10, 10),
        (7, 'Keyboard', 800, 100, 200, 200, 100),
        (8, 'Lamp', 200, 100, 50, 100, 50);
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}