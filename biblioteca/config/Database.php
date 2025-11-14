<?php
//aqui se configura la base de datos

class Database {
    private string $host = '127.0.0.1'; 
    private string $db_name = 'biblioteca';
    private string $username = 'root';
    private string $password = ''; 
    public ?PDO $conn;

    public function getConnection(): ?PDO {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password); 
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            return null;
        }
        return $this->conn;
    }
}