<?php
class ProblemsModel extends Model
{
    public function __construct()
    {
        parent::__construct();

    }
    public function sortByDifficulty($type)
    {
        if($type=="1")
            $type="ASC";
        else
            $type="DESC";
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare("SELECT p.id, p.name, p.description, p.nr_attempts, p.nr_successes, p.tags, COALESCE(AVG(c.grade), 0) AS average_grade
                                            FROM problems p
                                            LEFT JOIN all_comments c ON p.id = c.id_problem
                                            GROUP BY p.id, p.name, p.description, p.tags, p.nr_attempts, p.nr_successes
                                            ORDER BY average_grade ".$type);
        //$stmt->bindValue(1, $type);
        $stmt->execute();
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[] = $this->processRow($row);
        }
        $this->connectionPool->closeConnection($connection);
        return $data;
    }
    public function update(int $id, array $data):bool
    {
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare("select count(*) as cnt from problems where id=:id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        if($stmt->fetch(PDO::FETCH_ASSOC)["cnt"]==0)
        {
            return false;
        }
        $columns = array_keys($data);
        $sql =  "update problems set ".implode(" , ", array_map(function ($column){ return "$column = :$column";}, $columns))." where id = :id" ;

        $stmt =  $connection->prepare($sql);
        foreach ($data as $key => $value) {
            if($key=="tags"){
                $stmt->bindValue(":$key", '{'.addslashes(implode(",",$data["tags"])).'}');

            }
            else{
                $stmt->bindValue(":$key", $value);
            }
        }

        $stmt->bindValue(":id", $id );
        $stmt->execute();
        $this->connectionPool->closeConnection($connection);
        return true;
    }

    public function getAll($limit = 9999999):array
    {

        $sql = "SELECT p.id, p.name, p.description, p.nr_attempts, p.nr_successes, p,p.tags, COALESCE(AVG(c.grade), 0) AS average_grade
                                            FROM problems p
                                            LEFT JOIN all_comments c ON p.id = c.id_problem
                                            GROUP BY p.id, p.name, p.description, p.tags, p.nr_attempts, p.nr_successes order by p.id
                                            LIMIT (?) ";
        //$sql = "select id, name, description, tags, nr_attempts, nr_successes from problems LIMIT (?)";
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare($sql);
        $stmt->bindValue(1, $limit);
        $stmt->execute();
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[] = $this->processRow($row);
        }
        $this->connectionPool->closeConnection($connection);
        return $data;
    }

    public function sortByLike($field, $value)
    {
        $sql= "SELECT p.id, p.name, p.description, p.nr_attempts, p.nr_successes,p.tags, COALESCE(AVG(c.grade), 0) AS average_grade
                                            FROM problems p
                                            LEFT JOIN all_comments c ON p.id = c.id_problem
                                            GROUP BY p.id, p.name, p.description, p.tags, p.nr_attempts, p.nr_successes having ". $field ." LIKE "." ? order by p.id";


        $connection =  $this->connectionPool->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, '%' . $value . '%');
        $stmt->execute();
        $data = [];
        while ($row =  $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$this->processRow($row);
        }

        $this->connectionPool->closeConnection($connection);
        return $data;
    }
    public function sortLimit($field, $limit, $order)
    {
        $sql="SELECT p.id, p.name, p.description, p.nr_attempts, p.nr_successes,p.tags, COALESCE(AVG(c.grade), 0) AS average_grade
                                            FROM problems p
                                            LEFT JOIN all_comments c ON p.id = c.id_problem
                                            GROUP BY p.id, p.name, p.description, p.tags, p.nr_attempts, p.nr_successes
                                            order by ".$field." ". $order." limit :limit";


        #$sql = "select id, name, description, tags, nr_attempts, nr_successes from problems order by ".$field." ". $order." limit :limit";
        $connection=  $this->connectionPool->getConnection();
        $stmt =  $connection->prepare($sql);
        $stmt->bindValue(":limit", $limit);
        $stmt->execute();
        $data = [];
        while ($row =  $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$this->processRow($row);
        }

        $this->connectionPool->closeConnection($connection);
        return $data;

    }
    public function get(int $id):array
    {
        $sql = "select id, name, description, tags, nr_attempts, nr_successes from problems where id= (?)";
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $row=$this->processRow($row);
        $this->connectionPool->closeConnection($connection);
        return $row;
    }
    private function processRow($row)
    {
        $row["id"] = intval($row["id"]);
        $row["nr_attempts"] = intval($row["nr_attempts"]);
        $row["nr_successes"] = intval($row["nr_successes"]);
        $tags = explode("," ,substr($row["tags"],1,-1));
        foreach($tags as &$tag)
        {
            $tag=stripslashes($tag);
            if($tag[0]=='"')
                $tag=substr($tag, 1,-1);
        }
        $row["tags"]= $tags;

        return $row;
    }
    public function delete(int $id):void
    {
        $sql = "delete from problems where id= (?)";
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $this->connectionPool->closeConnection($connection);
    }
    public function create(array $data): int
    {
        $sql =  "INSERT INTO problems (name, description, tags, nr_attempts, nr_successes) values (?, ? ,?,?,?)";
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare($sql);
        $stmt->bindValue(1,strip_tags($data["name"]), PDO::PARAM_STR);
        $stmt->bindValue(2,strip_tags($data["description"]));
        #echo var_dump($data["tests"]);
        $stmt->bindValue(3,strip_tags('{'.addslashes(implode(",",$data["tags"])).'}'));
        //$stmt->bindValue(4, json_encode([" "=>' ']));
        $stmt->bindValue(4,0);
        $stmt->bindValue(5,0);
        $stmt->execute();
        $this->connectionPool->closeConnection($connection);
        return intval($connection->lastInsertId());
    }
}