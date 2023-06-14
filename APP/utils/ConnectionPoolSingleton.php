<?php
declare(strict_types=1);

class ConnectionPoolSingleton
{
    private static ConnectionPool $pool;
    public static function getPool()
    {
        if(!isset(self::$pool))
        {
            self::$pool =  new ConnectionPool(4, 2, 8);
            self::$pool->init("127.0.0.1", 5432, "web3", "pgusername", "pgpassword");
            return self::$pool;
        }
        else
        {
            return self::$pool;
        }
    }


}