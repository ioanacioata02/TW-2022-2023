<?php

require_once "utils/ConnectionPoolSingleton.php";
abstract class Model
{
    protected $connectionPool;
    public function __construct()
    {
        $this->connectionPool = ConnectionPoolSingleton::getPool();
    }

    private function validateCredentials(string $username, string $password): bool
    { 
    require_once("../utils/ConnectionPool.php");
$connectionPool = new ConnectionPool();
$connection = $connectionPool->getConnection();
$nume = $_POST['username'];
$pass = $_POST['password'];

$stm = $connection->prepare("SELECT * FROM users WHERE username=?");
$stm->bindValue(1, $username);
$res = $stm->execute();

$rows = $stm->fetchAll(PDO::FETCH_NUM);

if (password_verify($pass, $rows[0][2])) echo "Autentificat";
else echo "Neautorizat";

return password_verify($pass, $rows[0][2]);

    }

}