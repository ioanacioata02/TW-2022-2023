<?php
class CommentsModel extends Model
{
    public function __construct()
    {
        parent::__construct();

    }
    


    public function get(int $id):array
    {
        $sql = "select * from all_comments where id_problem= (?)";
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rows = $this->processRows($rows);
        $this->connectionPool->closeConnection($connection);
        return $rows;
    }
    private function processRow($row)
    {
        $row["id_user"] = intval($row["id_user"]);
        $row["id_problem"] = intval($row["id_problem"]);
        $row["comments_txt"] = $row["comments_txt"];
        $row["text"] = $row["text"];
       
        return $row;
    }
    
    private function processRows(array $rows): array
    {
        $processedRows = [];
    
        foreach ($rows as $row) {
            $processedRow = [
                "id_user" => intval($row["id_user"]),
                "id_problem" => intval($row["id_problem"]),
               "comment_txt" => strval($row["comment_txt"]),
               "title" => strval($row["title"]),
               "moment" => date("Y-m-d H:i:s", strtotime($row["moment"]))
               
            ];
        
            $processedRows[] = $processedRow;

        }
        return $processedRows;
    }
    public function addComment($id,$userId,$title,$comment_txt): bool {
        try {
            $sql = "INSERT INTO all_comments (id_user, id_problem,comment_txt,title) VALUES (?, ?, ?, ?)";
            $connection = $this->connectionPool->getConnection();
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->bindValue(2, $userId, PDO::PARAM_INT);
            $stmt->bindValue(3, $title, PDO::PARAM_STR);
            $stmt->bindValue(4, $comment_txt, PDO::PARAM_STR);
            $success = $stmt->execute();
            return $success;
        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
    
        return false;
    }
}