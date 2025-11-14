<?php

class Book {
    private PDO $conn;
    private string $book_table = "book";
    private string $book_author_table = "book_author";

    public int $book_id;
    public string $title;
    public string $isbn;
    public ?int $publication_year;
    public int $user_id; 
    public array $author_ids = []; 

    public function __construct(PDO $db) { $this->conn = $db; }

    // Obtiene todos los libros, uniendo los autores.     
    public function readAll(): PDOStatement {
        $query = "
            SELECT 
                b.book_id, b.title, b.isbn, b.publication_year, 
                GROUP_CONCAT(CONCAT(a.first_name, ' ', a.last_name) SEPARATOR ', ') AS authors
            FROM 
                " . $this->book_table . " b
            LEFT JOIN 
                " . $this->book_author_table . " ba ON b.book_id = ba.book_id
            LEFT JOIN 
                author a ON ba.author_id = a.author_id
            GROUP BY 
                b.book_id, b.title, b.isbn, b.publication_year
            ORDER BY 
                b.title ASC";
                
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function readOne(): ?array {
// aqui es para ver los detalles de un libro
        $query = "SELECT b.*, GROUP_CONCAT(ba.author_id) as author_ids FROM " . $this->book_table . " b
                  LEFT JOIN " . $this->book_author_table . " ba ON b.book_id = ba.book_id 
                  WHERE b.book_id = ? GROUP BY b.book_id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->book_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            $book['author_ids'] = $book['author_ids'] ? explode(',', $book['author_ids']) : [];
            return $book;
        }
        return null;
    }

    public function create(): bool {
        try {
            $this->conn->beginTransaction();
            $queryBook = "INSERT INTO " . $this->book_table . " SET title=:title, isbn=:isbn, publication_year=:year, user_id=:user_id";
            $stmtBook = $this->conn->prepare($queryBook);
            $stmtBook->bindParam(':title', $this->title);
            $stmtBook->bindParam(':isbn', $this->isbn);
            $stmtBook->bindParam(':year', $this->publication_year);
            $stmtBook->bindParam(':user_id', $this->user_id);
            $stmtBook->execute();
            $this->book_id = $this->conn->lastInsertId();

            if (!empty($this->author_ids)) {
                $queryAuthors = "INSERT INTO " . $this->book_author_table . " (book_id, author_id) VALUES ";
                $values = [];
                foreach ($this->author_ids as $author_id) {
                    $values[] = "({$this->book_id}, " . (int)$author_id . ")";
                }
                $queryAuthors .= implode(', ', $values);
                $this->conn->exec($queryAuthors);
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    public function update(): bool {
        try {
            $this->conn->beginTransaction();
            $queryBook = "UPDATE " . $this->book_table . " SET title=:title, isbn=:isbn, publication_year=:year WHERE book_id=:id";
            $stmtBook = $this->conn->prepare($queryBook);
            $stmtBook->bindParam(':title', $this->title);
            $stmtBook->bindParam(':isbn', $this->isbn);
            $stmtBook->bindParam(':year', $this->publication_year);
            $stmtBook->bindParam(':id', $this->book_id);
            $stmtBook->execute();

            $queryDelete = "DELETE FROM " . $this->book_author_table . " WHERE book_id = ?";
            $stmtDelete = $this->conn->prepare($queryDelete);
            $stmtDelete->bindParam(1, $this->book_id);
            $stmtDelete->execute();

            if (!empty($this->author_ids)) {
                $queryAuthors = "INSERT INTO " . $this->book_author_table . " (book_id, author_id) VALUES ";
                $values = [];
                foreach ($this->author_ids as $author_id) {
                    $values[] = "({$this->book_id}, " . (int)$author_id . ")";
                }
                $queryAuthors .= implode(', ', $values);
                $this->conn->exec($queryAuthors);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    public function delete(): bool {
        $query = "DELETE FROM " . $this->book_table . " WHERE book_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->book_id);
        return $stmt->execute();
    }
}