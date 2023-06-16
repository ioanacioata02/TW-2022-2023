<?php

class HomeworkModel extends   Model
{
    public function __construct()
{
    parent::__construct();

}

public function postSolution($solution, $id_problem, $id_homework, $id_student)
{
    $connection = $this->connectionPool->getConnection();
    $stmt =  $connection->prepare("select problems, deadline from homework_members where
                                          id_homework=? and id_student=?");
    $stmt->bindValue(1, $id_homework);
    $stmt->bindValue(2, $id_student);
    $stmt->execute();
    $result =$stmt->fetchAll(PDO::FETCH_ASSOC);
    if(sizeof($result)==0)
    {
        return ["message"=>"Something went wrong"];
    }


    $result =  $result[0];
    $result["problems"]= (get_object_vars(json_decode($result["problems"])));
    //result =  get_object_vars(json_decode($stmt->fetchAll()["problems"]));
    //echo  var_dump(   $result["problems"][$id_problem]);
    $result["problems"][$id_problem]= get_object_vars( $result["problems"][$id_problem]);
    if($result["problems"][$id_problem]["status"]=="complete")
    {
        return ["message"=>"you already submited this solution"];
    }
    if(time()>$result["deadline"])
    {
        return ["message"=>"Time expired"];
    }
    $result["problems"][$id_problem]["status"]="complete";
    $result["problems"][$id_problem]["solution"]=htmlspecialchars($solution);
    $stmt=$connection->prepare("update homework_members set problems = ? where id_homework=? and id_student=?");
    $stmt->bindValue(1,json_encode($result["problems"]));
    $stmt->bindValue(2, $id_homework);
    $stmt->bindValue(3, $id_student);
    $stmt->execute();
    return ["message"=>"Solution posted"];


}
public function getProblems($id, $payload)
    {
        if($payload["status"]==0)
        {
            return $this->getStudentProblems($id, $payload["id"]);
        }

    }

    public function getStudentProblems($id_homework, $id_student)
    {
        $connection  = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare("select u.username as username_student, hm.id_homework, hm.id_student, hm.problems, hm.deadline  from homework_members hm join users u on u.id=hm.id_student where hm.id_homework=? AND hm.id_student=?");
        $stmt->bindValue(1, $id_homework);
        $stmt->bindValue(2, $id_student);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        $result["problems"]=get_object_vars(json_decode($result["problems"]));
        foreach (array_keys($result["problems"]) as $key)
        {
            if(gettype($result["problems"][$key])=="string")
            {
                $result["problems"][$key]= get_object_vars( json_decode( $result["problems"][$key]));
            }
            else
                $result["problems"][$key]= get_object_vars($result["problems"][$key]);
            $stmt =  $connection->prepare("select name from problems where id=? ");
            $stmt->bindValue(1, $key);
            $stmt->execute();
            $result["problems"][$key]["name"]=$stmt->fetchColumn(0);

        }
        return $result;
    }
    public function getStudentsFromHomework($id)
    {
        $connection = $this->connectionPool->getConnection();

        $stmt = $connection->prepare("SELECT u.id, u.first_name, u.last_name,u.username
                                            FROM users u
                                            JOIN homework_members hm ON u.id = hm.id_student
                                            JOIN homeworks h ON hm.id_homework = h.id
                                            WHERE h.id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
       
      $stmt= $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $stmt;
    }
    public function getHomeworks($payload)
    {
        if($payload["status"]==0)
        {
            return $this->getStudentHomeworks($payload["id"]);
        }
        if($payload["status"]==1)
        {

            return $this->getTeacherHomeworks($payload["id"]);
        }
    }

/*    private function getStudentHomeworks($id_homework, $id_student)
    {

    }*/

    private function getStudentHomeworks($id)
    {

        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare("SELECT h.id, h.topic, h.author, h.deadline
                                            FROM homeworks h
                                            JOIN homework_members hm ON h.id = hm.id_homework
                                            WHERE hm.id_student = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    private function getTeacherHomeworks($id)
    {

        $connection = $this->connectionPool->getConnection();
        $stmt  =  $connection->prepare("select topic, deadline, id from homeworks where author = ?  ");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createHomework($data)
    {
        if(!isset($data["author"]) || !isset($data["topic"]) ||  !isset($data["deadline"]) || !isset($data["id_classes"]) || !isset($data["id_problems"]))
        {
            http_response_code(400);
            return [ "message"=>"The body must contain topic, deadline, id_classes, author and id_problems"];

        }
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare("insert into homeworks (topic, deadline, problems_id, author) values (?, ?, ?,?)");
        $stmt->bindValue(1, $data["topic"]);
        $stmt->bindValue(2, time() + 3600 * $data["deadline"]);
        $stmt->bindValue(3, strip_tags( '{'. implode(" , ",$data["id_problems"])."}"));
        $stmt->bindValue(4, $data["author"]);

        $stmt->execute();
        $homework_id=intval($connection->lastInsertId());
        $map = json_encode(array_fill_keys($data["id_problems"], json_encode(["status" => "incomplete", "solution" => ""])));
        foreach ($data["id_classes"] as $id_class)
        {

            $stmt = $connection->prepare("select id_user from class_members where id_class = ?");
            $stmt->bindValue(1, $id_class);
            $stmt->execute();
            $students = array_values( $stmt->fetchAll(PDO::FETCH_ASSOC));
            foreach ($students as $id_student)
            {

                $stmt =  $connection->prepare("insert into homework_members (id_homework, id_student, problems, deadline ) values (? , ? , ?,?)");
                $stmt->bindValue(1, $homework_id);
                $stmt->bindValue(2, $id_student["id_user"]);
                $stmt->bindValue(3, $map);
                $stmt->bindValue(4, time() + 3600 * $data["deadline"]);
                $stmt->execute();

            }

        }
        http_response_code(201);
        return [ "id"=>$homework_id, "message" => "success"];


    }

}