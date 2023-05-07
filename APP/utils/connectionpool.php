<?php
declare(strict_types=1);

class ConnectionPool
{
    private SplQueue $connections;
    private int $initialSize;
    private int $minimumSize;
    private int $maxSize;
    private int $currentConnectionsCount;
    private bool $keepOpen;
    private string $host;
    private int $port;
    private string $dbname;
    private string $envUsername;
    private string $envPassword;

    /**
     * Will only initialize some values there won't be a connection to the database to initialize the connection use init
     */
    private function initLogFile():void
    {
        if(!is_dir("logs"))
        {
            mkdir("logs", 0755, true);
        }
    }
    public function __construct(int $initialSize, int $minimumSize, int $maxSize, bool $keepOpen = true)
    {
        $this->initialSize = $initialSize;
        $this->minimumSize = $minimumSize;
        $this->maxSize = $maxSize;
        $this->currentConnectionsCount = 0;
        $this->keepOpen = $keepOpen;
        $this->connections=new SplQueue();
        $this->initLogFile();
    }

    /**
     * Will make $initialSize connection and put them in a queue use getConnection to extract a connection
     */
    public function init(string $host, int $port, string $dbname, string $envUsername, string $envPassword): void
    {

        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->envUsername = $envUsername;
        $this->envPassword = $envPassword;
        $username = getenv($envUsername);
        $password = getenv($envPassword);


        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
        for (; $this->currentConnectionsCount < $this->initialSize; $this->currentConnectionsCount++) {
            try {
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connections->enqueue($pdo);
                //todo remove after testing
                $message = date('Y-m-d H:i:s') . ", Connected to the database\n";
                file_put_contents('logs'.DIRECTORY_SEPARATOR.'database_log.txt', $message, FILE_APPEND);
            } catch (PDOException $e) {

                $message = date('Y-m-d H:i:s') . ", Connection failed: " . $e->getMessage() . "\n";
                file_put_contents('logs'.DIRECTORY_SEPARATOR.'database_log.txt', $message, FILE_APPEND);
            }
        }
    }



    /**
     *This method will return a connection to the database if there are no available connections and the currentConnectionsCount has reached the maxSize function will
     * return null otherwise the function will return a connection from the queue or will initialize a new one
     */

    public function getConnection(): ?object
    {
        if ($this->connections->isEmpty() && $this->currentConnectionsCount == $this->maxSize) {
            return null;
        } else if (!$this->connections->isEmpty()) {
            return $this->connections->dequeue();
        }
        return $this->createConnection();
    }

    private function createConnection(): PDO
    {
        $username = getenv($this->envUsername);
        $password = getenv($this->envPassword);
        $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;";
        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->currentConnectionsCount++;
            //todo remove after testing
            $message = date('Y-m-d H:i:s') . ", Connected to the database\n";
            file_put_contents('logs'.DIRECTORY_SEPARATOR.'database_log.txt', $message, FILE_APPEND);
        } catch (PDOException $e) {

            $message = date('Y-m-d H:i:s') . ", Connection failed: " . $e->getMessage() . "\n";
            file_put_contents('logs'.DIRECTORY_SEPARATOR.'database_log.txt', $message, FILE_APPEND);
        }
        return $pdo;

    }

    public function resize(int $maxSize): void
    {
        $this->maxSize = $maxSize;

        while ($this->currentConnectionsCount > $this->maxSize) {
            $connection = $this->connections->dequeue();
            $connection = null;
            $this->currentConnectionsCount--;
        }
    }

    public function getCurrentConnectionsCount(): int
    {
        return $this->currentConnectionsCount;
    }

    public function closeConnection(PDO $connection): void
    {
        if ($this->keepOpen) {
            $this->connections->enqueue($connection);
            $connection = null;
        } else {
            $connection = null;
        }
    }

    public function destroy(): void
    {
        while (!$this->connections->isEmpty()) {
            $connection = $this->connections->dequeue();
            $connection = null;
            $this->currentConnectionsCount--;
        }
    }

}