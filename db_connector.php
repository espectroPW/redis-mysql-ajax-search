<?php
require_once __DIR__ . '/bootstrap.php';

class MySQLConnector {
    private $connection;
    
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        try {
            $this->connection = new mysqli(
                $_ENV['MYSQL_HOST'],
                $_ENV['MYSQL_USER'],
                $_ENV['MYSQL_PASS'],
                $_ENV['MYSQL_DB'],
                $_ENV['MYSQL_PORT'] ?? 3306
            );
            
            if ($this->connection->connect_error) {
                throw new Exception("B³¹d po³¹czenia: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log("B³¹d podczas ³¹czenia z MySQL: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function fetchAllData($query) {
        try {
            $result = $this->connection->query($query);
            if (!$result) {
                throw new Exception("B³¹d zapytania: " . $this->connection->error);
            }
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            return $data;
        } catch (Exception $e) {
            error_log("B³¹d podczas pobierania danych: " . $e->getMessage());
            return [];
        }
    }
    
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    public function __destruct() {
        $this->close();
    }
}