<?php

class ProfileModel extends Model{
    public static $nrOfProbPerPage = 30;

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
        return $row;
    }

    public function getOwnSubmits(array $input):array{
        $data=[];

        try{
            $offset = ($input['page'] - 1) * self::$nrOfProbPerPage;
            $sql = "SELECT s.id, s.id_problem, p.name, s.moment FROM solutions s JOIN problems p on s.id_problem = p.id WHERE id_user = (?) ORDER BY moment DESC LIMIT (?) OFFSET (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $input['id'], PDO::PARAM_INT);
            $stmt->bindValue(2, self::$nrOfProbPerPage, PDO::PARAM_INT);
            $stmt->bindValue(3, $offset, PDO::PARAM_INT);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $data;
    }

    public function getSubmits(array $input):array{
        $data=[];

        try{
            $offset = ($input['page'] - 1) * self::$nrOfProbPerPage;
            $sql = "SELECT s.id_problem, p.name, s.moment FROM solutions s JOIN problems p on s.id_problem = p.id WHERE id_user = (?) ORDER BY moment DESC LIMIT (?) OFFSET (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $input['id'], PDO::PARAM_INT);
            $stmt->bindValue(2, self::$nrOfProbPerPage, PDO::PARAM_INT);
            $stmt->bindValue(3, $offset, PDO::PARAM_INT);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $data;
    }

    public function getNrOfSubmits($id): int{
        $nrOfSubmits = -1;
        try {
            $sql = "SELECT COUNT(*) FROM solutions WHERE id_user = (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $nrOfSubmits = intval($stmt->fetchColumn());
        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $nrOfSubmits;
    }

    public function userExists(int $id): bool{
        $response = true;
        try{
            $sql = "select * from users where id= (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(["id" => $id, "message" => "User doesn't exist"]);
                $response = false;
            }
        }catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
            $response = false;
        }finally{
            $this->connectionPool->closeConnection($connection);
        }
        return $response;
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
                //$row = $stmt->fetch(PDO::FETCH_ASSOC);
                //$row = $this->processRow($row);

                $sql = "UPDATE users SET password = (?) WHERE id = (?)";
                $stmt =  $connection->prepare($sql);
                $hash = password_hash($data["password"], PASSWORD_DEFAULT);
                //echo password_verify($data["password"], $row["password"]);
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