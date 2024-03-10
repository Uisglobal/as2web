<?php
class Database
{
        private $server = "localhost";
        private $username = "root";
        private $password = "";
        private $db_name = "cakewebsite";
        private $conn;
    
        public function __construct()
        {
            try {
                $this->conn = new PDO("mysql:host={$this->server};dbname={$this->db_name}", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    
        public function getConnection()
        {
            return $this->conn;
        }
    
}


?>