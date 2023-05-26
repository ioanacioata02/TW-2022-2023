<?php
class ProblemsModel extends Model
{
    public function __construct()
    {
        parent::__construct();

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
            if($key=="tags" || $key=="tests")
            $stmt->bindValue(":$key", json_encode($value));
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
        $sql = "select * from problems LIMIT (?)";
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

    public function sortLimit($field, $limit, $order)
    {
        $sql = "select * from problems order by ".$field." ". $order." limit :limit";
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
        $sql = "select * from problems where id= (?)";
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
        $row["tests"]=json_decode($row["tests"]);
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

        $sql =  "INSERT INTO problems (name, description, tags, tests, nr_attempts, nr_successes) values (?, ?,? ,?,?,?)";
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare($sql);
        $stmt->bindValue(1,$data["name"], PDO::PARAM_STR);
        $stmt->bindValue(2,$data["description"]);
        #echo var_dump($data["tests"]);
        $stmt->bindValue(3,'{'.addslashes(implode(",",$data["tags"])).'}');
        $stmt->bindValue(4,json_encode($data["tests"]));
        $stmt->bindValue(5,0);
        $stmt->bindValue(6,0);
        $stmt->execute();
        $this->connectionPool->closeConnection($connection);
        return intval($connection->lastInsertId());
    }
}