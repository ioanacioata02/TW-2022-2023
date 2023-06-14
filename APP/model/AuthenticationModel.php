<?php

class AuthenticationModel extends Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function checkData($email, $password)
    {
     
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare("select id, username, status, password from users where email=?");
        $stmt->bindValue(1,$email,PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()==0)
        {

            return null;
        }
        $data  = $stmt->fetch(PDO::FETCH_ASSOC);
        $passwordDatabase =   $data["password"];
        //echo $passwordDatabase;
        if(password_verify($password, $passwordDatabase))
        {
            return $data;
        }
        return null;
    }
}