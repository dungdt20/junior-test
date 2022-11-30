<?php
require 'vendor/autoload.php';

use App\Systems\Database\DatabaseConnector;
use Dotenv\Dotenv;

$dotenv = Dotenv::create(__DIR__, '../.env');
$dotenv->load();

// test code, should output:
// api://default
// when you run $ php bootstrap.php
echo getenv('OKTAAUDIENCE');

$dbConnection = (new DatabaseConnector())->getConnection();
