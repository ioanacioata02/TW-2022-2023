<?php

class SolutionsModel extends Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public  function getSolutions($id_problem, $id)
    {
        $connection =  $this->connectionPool->getConnection();
        $stmt = $connection->prepare("select solution, TO_CHAR(moment ,'DD.MM.YYYY' ) as date from solutions where id_problem=? AND id_user=? ");
        $stmt->bindValue(1, $id_problem);
        $stmt->bindValue(2, $id);
        $stmt->execute();
        $this->connectionPool->closeConnection($connection);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function create($id_problem, $solution, $studentID)
    {
        $solution=htmlspecialchars($solution);


        $connection =  $this->connectionPool->getConnection();
        $connection->beginTransaction();
        $stmt =  $connection->prepare("insert into solutions(id_user, id_problem, solution, success) values (?, ?, ?, false)");
        $stmt->bindValue(1,$studentID);
        $stmt->bindValue(2,$id_problem);
        $stmt->bindValue(3,$solution);
        $stmt->execute();
        $stmt= $connection->prepare(   "update problems set nr_attempts = nr_attempts + 1 where id=?" );
        $stmt->bindValue(1, $id_problem);
        $stmt->execute();
        $stmt= $connection->prepare("update users set nr_attempts =nr_attempts +1 where id= ?");
        $stmt->bindValue(1,$studentID);
        $stmt->execute();
        $connection->commit();
        $this->connectionPool->closeConnection($connection);
        return ["message"=>"success"];


    }
}