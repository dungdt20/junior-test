<?php

use App\Controllers\OrderController;
use App\Controllers\ProductController;

require "../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($uri[1] === 'api') {
    if ($uri[2] === 'product') {
        $id = null;
        if (isset($uri[3])) {
            $id = (int) $uri[3];
        }

        $controller = new ProductController($dbConnection, $requestMethod, $id);
        $controller->processRequest();
        exit();
    }

    if ($uri[2] === 'order') {
        $id = null;
        if (isset($uri[3])) {
            $id = (int) $uri[3];
        }

        $controller = new OrderController($dbConnection, $requestMethod, $id);
        $controller->processRequest();
        exit();
    }
}

header("HTTP/1.1 404 Not Found");
exit();