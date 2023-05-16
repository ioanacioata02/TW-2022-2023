<?php

require_once "utils/ConnectionPoolSingleton.php";
abstract class Model
{
    protected $connectionPool;
    public function __construct()
    {
        $this->connectionPool = ConnectionPoolSingleton::getPool();
    }

}