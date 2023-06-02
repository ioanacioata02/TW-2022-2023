<?php

class RegisterModel extends Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function register($lastName, $fistName, $username, $password, $email, $type)
    {
        //todo check IF THE email already exists
        $passwordHash  = password_hash($password, PASSWORD_DEFAULT);
        $sql  = "insert into users (first_name, last_name, username, email, password, nr_attempts, nr_successes, status,img) values (?,?,?,?,?,?,?,?,?)";
        $connection =  $this->connectionPool->getConnection();
        $sqlVerify  = "select id from users where email=(?)";
        $stmtVerify =  $connection->prepare($sqlVerify);
        $stmtVerify->bindValue(1, $email);
        $stmtVerify->execute();
        if($stmtVerify->rowCount()>0)
        {
            Utils::throwError(409, ["message"=>"The email is already used"]);
            exit(0);
        }
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1,$fistName);
        $stmt->bindValue(2,$lastName);
        $stmt->bindValue(3,$username);
        $stmt->bindValue(4,$email);
        $stmt->bindValue(5,$passwordHash);
        $stmt->bindValue(6,0);
        $stmt->bindValue(7,0);
        $stmt->bindValue(8, $type);
        $stmt->bindValue(9, "");
        $stmt->execute();
        $this->connectionPool->closeConnection($connection);
        return intval($connection->lastInsertId());
    }
}