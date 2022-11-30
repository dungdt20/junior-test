<?php
namespace App\Systems\Database;

class RedisConnector
{

    private $redisConnetion = null;

    public function __construct()
    {
        $host = getenv('REDIS_HOST');
        $post = getenv('REDIS_POST');
        $user = getenv('REDIS_USERNAME');
        $pass = getenv('REDIS_PASSWORD');

        try {
            $this->redisConnetion = new \Redis();
            $this->redisConnetion->connect($host, $post);

        } catch (\RedisException $e) {
            exit($e->getMessage());
        }
    }

    public function getRedisConnection(): \Redis
    {
        return $this->redisConnetion;
    }
}

