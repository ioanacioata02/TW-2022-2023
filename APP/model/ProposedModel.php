<?php

class ProposedModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function getPage($params):array{
        $data = null;

        try {
            $limit = intval($params['limit']);
            $page = intval($params['page']);
            $offset = ($page - 1) * $limit;

            $sql = "SELECT * FROM proposed_problems ORDER BY id LIMIT (?) OFFSET (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $this->processRow($row);
            }
        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $data ?? [];
    }

    public function get(int $id):array{
        $row = null;

        try{
            $sql = "SELECT * FROM proposed_problems WHERE id = (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            $rowCount = $stmt->rowCount();
            if($rowCount === 1){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $row=$this->processRow($row);
            }
            else{
                $row=[];
            }
        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $row;
    }

    private static function processRow($row):array{
        $row["id"] = intval($row["id"]);
        $row["tags"]= isset($row["tags"]) ? json_decode($row["tags"]) : null;
        $row["tests"]=json_decode($row["tests"]);
        $row["id_author"] = intval($row["id_author"]);

        return $row;
    }

    public function reject(int $id):bool{
        $response = true;
        try{
            $sql = "delete from proposed_problems where id= (?)";
            $connection = $this->connectionPool->getConnection();
            $stmt =  $connection->prepare($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(["id" => $id, "message" => "Proposed problem not found"]);
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

    public function create(array $data): int{
        $result = -1;
        if(! $this->checkData($data)){
            return $result;
        }

        try{
            $connection = $this->connectionPool->getConnection();

            if(isset($data["tags"])){
                $sql =  "INSERT INTO proposed_problems (name, description, tags, tests, id_author) values (?, ?, ?, ?, ?)";
                $stmt =  $connection->prepare($sql);
                $stmt->bindValue(1, $data["name"], PDO::PARAM_STR);
                $stmt->bindValue(2, $data["description"], PDO::PARAM_STR);
                $stmt->bindValue(3, json_encode($data["tags"]));
                $stmt->bindValue(4, json_encode($data["tests"]));
                $stmt->bindValue(5, $data["id_author"], PDO::PARAM_INT);
                $stmt->execute();
            }
            else{
                $sql =  "INSERT INTO proposed_problems (name, description, tests, id_author) values (?, ?, ?, ?)";
                $stmt =  $connection->prepare($sql);
                $stmt->bindValue(1, $data["name"], PDO::PARAM_STR);
                $stmt->bindValue(2, $data["description"], PDO::PARAM_STR);
                $stmt->bindValue(3, json_encode($data["tests"]));
                $stmt->bindValue(4, $data["id_author"], PDO::PARAM_INT);
                $stmt->execute();
            }
        }catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        }finally{
            $this->connectionPool->closeConnection($connection);
        }
        return intval($connection->lastInsertId());
    }

    private static function checkData(array $data): bool{
        $requiredKeys = ["name", "description", "tests", "id_author"];
        $filteredData = array_intersect_key($data, array_flip($requiredKeys));
        
        if (count($filteredData) !== count($requiredKeys)) {
            http_response_code(400);
            echo json_encode(["message" => "Bad request"]);
            return false;
        }

        return true;
    }

    public function accept(int $id):bool{
        $response = true;
        try{
            $connection = $this->connectionPool->getConnection();
            
            $connection->beginTransaction();
            $stmt =  $connection->prepare("CALL accept_probl(?)");
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            $connection->commit();
            
        }catch (Throwable $exception) {
            if ($connection !== null) {
                $connection->rollBack();
            }
            ErrorHandler::handleException($exception);
            $response = false;
        }finally{
            $this->connectionPool->closeConnection($connection);
        }
        return $response;
    }
}