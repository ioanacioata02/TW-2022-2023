<?php

class ProfileModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function get(int $id):array{
        $row=[];

        try{
            $sql = "SELECT id, status, first_name, last_name, username, email, nr_attempts, nr_successes, img FROM users WHERE id = (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            $rowCount = $stmt->rowCount();
            if($rowCount === 1){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $row = $this->processRow($row);
            }
        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $row;
    }

    private static function processRow(array $row):array{

        $row["id"] = intval($row["id"]);
        $status = intval($row["status"]);
        switch($status){
            case 0:
                $row['role']="Student";
                break;

            case 1:
                $row['role']="Professor";
                break;
            
            case 2:
                $row['role']="Admin";
                break; 
        }
        unset($row['status']);
        $row["nr_attempts"] = intval($row["nr_attempts"]);
        $row["nr_successes"] = intval($row["nr_successes"]);
        return $row;
    }

    public function changePass(array $data):bool{
        $response = true;
        try{
            $sql="SELECT * FROM users WHERE id = (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $data["id"], PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(["email" => $email, "message" => "User not found"]);
                $response = false;
            }
            else{

                $sql = "UPDATE users SET password = (?) WHERE id = (?)";
                $stmt =  $connection->prepare($sql);
                $hash = password_hash($data["password"], PASSWORD_DEFAULT);
                $stmt->bindValue(1, $hash, PDO::PARAM_STR);
                $stmt->bindValue(2, $data["id"], PDO::PARAM_INT);
                $stmt->execute();
            }

        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
            $response = false;
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $response;
    }

    public function changeImg(array $data):bool{
        $response = true;
        try{
            $sql="SELECT * FROM users WHERE id = (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $data["id"], PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(["email" => $email, "message" => "User not found"]);
                $response = false;
            }
            else{
                $sql = "UPDATE users SET img = (?) WHERE id = (?)";
                $stmt =  $connection->prepare($sql);
                $stmt->bindValue(1, $data["image"], PDO::PARAM_STR);
                $stmt->bindValue(2, $data["id"], PDO::PARAM_INT);
                $stmt->execute();
            }

        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
            $response = false;
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $response;
    }
}