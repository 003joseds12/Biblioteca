<?php

class Author {
    private PDO $conn;
    private string $table_name = "author";

    public int $author_id;
    public string $first_name;
    public string $last_name;
    public ?int $birth_year; 

    public function __construct(PDO $db) { $this->conn = $db; }

    public function readAll(): PDOStatement {
        $query = "SELECT author_id, first_name, last_name, birth_year FROM " . $this->table_name . " ORDER BY last_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function readOne(): ?Author {
        $query = "SELECT author_id, first_name, last_name, birth_year FROM " . $this->table_name . " WHERE author_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->author_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->birth_year = $row['birth_year'] ? (int)$row['birth_year'] : null;
            return $this;
        }
        return null;
    }

    public function create(): bool {
        $query = "INSERT INTO " . $this->table_name . " SET first_name=:first_name, last_name=:last_name, birth_year=:birth_year";
        $stmt = $this->conn->prepare($query);
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->birth_year = $this->birth_year ? (int)$this->birth_year : null;
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":birth_year", $this->birth_year);
        return $stmt->execute();
    }

    public function update(): bool {
        $query = "UPDATE " . $this->table_name . " SET first_name=:first_name, last_name=:last_name, birth_year=:birth_year WHERE author_id = :author_id";
        $stmt = $this->conn->prepare($query);
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->birth_year = $this->birth_year ? (int)$this->birth_year : null;
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':birth_year', $this->birth_year);
        $stmt->bindParam(':author_id', $this->author_id);
        return $stmt->execute();
    }

    public function delete(): bool {
        $query = "DELETE FROM " . $this->table_name . " WHERE author_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->author_id);
        return $stmt->execute();
    }
}