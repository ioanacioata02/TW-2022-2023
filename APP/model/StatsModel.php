<?php

class StatsModel extends Model
{
    public function __construct(){
        parent::__construct();
    }

    public function get():array{
        $results=[];

        try{
            $connection = $this->connectionPool->getConnection();

            // nr of users
            $sql = "SELECT COUNT(*) FROM users";
            $stmt =  $connection->prepare($sql);
            $stmt->execute();

            $results["nr_users"] = intval($stmt->fetchColumn());

            // nr of problems
            $sql = "SELECT COUNT(*) FROM problems";
            $stmt =  $connection->prepare($sql);
            $stmt->execute();

            $results["nr_probl"] = intval($stmt->fetchColumn());

            // nr of attempted problems
            $sql = "SELECT COUNT(*) FROM problems WHERE nr_attempts > 0";
            $stmt =  $connection->prepare($sql);
            $stmt->execute();

            $results["nr_attempted_probl"] = intval($stmt->fetchColumn());
        } catch (Throwable $exception) {
            ErrorHandler::handleException($exception);
        } finally {
            $this->connectionPool->closeConnection($connection);
        }
        return $results;
    }
}