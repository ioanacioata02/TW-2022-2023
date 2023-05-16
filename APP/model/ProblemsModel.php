<?php
class ProblemsModel extends Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getAll():array
    {
        $sql = "select * from problems";
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $row["id"] = intval($row["id"]);
            $row["nr_attempts"] = intval($row["nr_attempts"]);
            $row["nr_successes"] = intval($row["nr_successes"]);

            $data[] = $row;
        }
        $this->connectionPool->closeConnection($connection);
        return $data;
    }
    public function create(array $data): int
    {

        $sql =  "INSERT INTO problems (name, description, tags, tests, nr_attempts, nr_successes) values (?, ?,? ,?,?,?)";
        $connection = $this->connectionPool->getConnection();
        $stmt =  $connection->prepare($sql);
        $stmt->bindValue(1,$data["name"], PDO::PARAM_STR);
        $stmt->bindValue(2,$data["description"]);
        //$tags = Utils::toPostgresArray($data["tags"]);
        $tags = implode(',', $data["tags"]);  // Convert array to a comma-separated string
        //echo $tags."\n";
        $stmt->bindValue(3, $tags, PDO::PARAM_STR);
        $stmt->bindValue(4,$data["tests"]);
        $stmt->bindValue(5,$data["nr_attempts"]);
        $stmt->bindValue(6,$data["nr_successes"]);
        $stmt->execute();
        $id =  intval($connection->lastInsertId());
        return $id;
    }
}