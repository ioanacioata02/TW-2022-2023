<?php

class PromoteModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function promote(int $status, $email):bool{
        $response = true;

        try{
            $sql="SELECT * FROM users WHERE email = (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(["email" => $email, "message" => "Email not found"]);
                $response = false;
            }
            else{
                $sql = "select * from users where email = (?) AND status = (?)";
                $stmt = $connection->prepare($sql);
                $stmt->bindValue(1, $email, PDO::PARAM_STR);
                $stmt->bindValue(2, $status, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    http_response_code(409);
                    echo json_encode(["email" => $email, "status" => $status, "message" => "User already has this role"]);
                    $response = false;
                }
                else{ // continue
                    $sql = "update users set status = (?) where email = (?)";
                    $stmt =  $connection->prepare($sql);
                    $stmt->bindValue(1, $status, PDO::PARAM_INT);
                    $stmt->bindValue(2, $email, PDO::PARAM_STR);
                    $stmt->execute();
            
                    if ($stmt->rowCount() === 0) {
                        http_response_code(500);
                        echo json_encode(["message" => "Internal server error"]);
                        $response = false;
                    }
                }
            }

        }catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
            $response = false;
        }finally{
            $this->connectionPool->closeConnection($connection);
        }
        return $response;
    }

}