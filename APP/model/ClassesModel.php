<?php

class ClassesModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function getForUser(int $id):array{
        $rows=[];

        try{
            $sql = "SELECT c.id, c.name, COUNT(cm.id_user) AS numMembers
            FROM classes AS c
            INNER JOIN class_members AS cm ON c.id = cm.id_class
            WHERE cm.id_user = ?
            GROUP BY c.id, c.name";
    $connection = $this->connectionPool->getConnection();
    $stmt = $connection->prepare($sql);
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rows = $this->processRows($rows);

        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $rows;
    }
    private function processRows(array $rows): array
    {
        $processedRows = [];
    
        foreach ($rows as $row) {
            $id= $row["id"];
            $numMembers = $this->getNumberOfMembers($id);
    
            $processedRow = [
                "id" => intval($row["id"]),
                "name" => strval($row["name"]),
                "numMembers" => $numMembers
            ];
    
            $processedRows[] = $processedRow;
        }
    
        return $processedRows;
    }
    private function processRowId(array $rows): array
    {
        $processedRows = [];
    
        foreach ($rows as $row) {
            $processedRow = [
                "id" => intval($row["id"]),
                "first_name" => strval($row["first_name"]),
                "last_name" => strval($row["last_name"]),
                "username" => strval($row["username"])
            ];
        
            $processedRows[] = $processedRow;

        }
        return $processedRows;
    }
    public function getNrOfClasses(int $id):int{

        try{
            $sql = "SELECT c.id, c.name
            FROM classes AS c
            INNER JOIN class_members AS cm ON c.id = cm.id_class
            WHERE cm.id_user = ?";
    $connection = $this->connectionPool->getConnection();
    $stmt = $connection->prepare($sql);
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    return $rowCount;

        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
    
    }

  

    public function get(int $classId, int $userId):array{
        $rows=[];

        try{
            $sql = "SELECT u.id, u.first_name, u.last_name,u.username
            FROM classes AS c
            INNER JOIN class_members AS cm ON c.id = cm.id_class
            INNER JOIN users as u ON u.id=cm.id_user
            WHERE c.id = ?";
    $connection = $this->connectionPool->getConnection();
    $stmt = $connection->prepare($sql);
    $stmt->bindValue(1, $classId, PDO::PARAM_INT);
    $stmt->execute();

    $rowCount = $stmt->rowCount();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rows = $this->processRowId($rows);
    

        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $rows;
    }

    private function getNumberOfMembers(int $id): int
{
    try {
        $sql = "SELECT COUNT(id_user) FROM class_members WHERE id_class = ?";
        $connection = $this->connectionPool->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $numMembers = $stmt->fetchColumn();
        return intval($numMembers);

    } catch (Throwable $exception) {
        ErrorHandler::handleException($exception);
    } finally {
        $this->connectionPool->closeConnection($connection);
    }

    return 0;
}

public function addMember(int $classId, int $userId): bool {
    try {
        $sql = "INSERT INTO class_members (id_class, id_user) VALUES (?, ?)";
        $connection = $this->connectionPool->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, $classId, PDO::PARAM_INT);
        $stmt->bindValue(2, $userId, PDO::PARAM_INT);
        $success = $stmt->execute();
        return $success;
    } catch (Throwable $exception) {
        ErrorHandler::handleException($exception);
    } finally {
        $this->connectionPool->closeConnection($connection);
    }

    return false;
}

public function createClass(string $className,int $userId): bool {
    try {
        $sql = "INSERT INTO classes (name) VALUES (?)";
        $connection = $this->connectionPool->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, $className, PDO::PARAM_STR);
        if($className==="error")
        return false;
       $success= $stmt->execute();
        if($success)
        {  $classId = $connection->lastInsertId();//functie oferita de pdo
            $success = $this->addCreator($classId, $userId);
        }
       
        return $success;
    } catch (Throwable $exception) {
        ErrorHandler::handleException($exception);
    } finally {
        $this->connectionPool->closeConnection($connection);
    }

    return false;
}

public function addCreator(int $classId,int $userId): bool {
   

    try {
        $sql = "INSERT INTO class_members (id_class, id_user) VALUES (?, ?)";
        $connection = $this->connectionPool->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, $classId, PDO::PARAM_INT);
        $stmt->bindValue(2, $userId, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (Throwable $exception) {
        ErrorHandler::handleException($exception);
    } finally {
        $this->connectionPool->closeConnection($connection);
    }
    
    return false;
}


    
}