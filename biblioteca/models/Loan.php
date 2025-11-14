<?php

class Loan {
    private PDO $conn;
    private string $table_name = "loan";

    public int $loan_id;
    public int $book_id;
    public int $user_id;
    public string $status = 'en_espera'; 

    public function __construct(PDO $db) { $this->conn = $db; }

    // Crea una nueva solicitud de préstamo por parte del usuario.
   
    public function createRequest(): bool {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET book_id=:book_id, user_id=:user_id, status='en_espera'";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':user_id', $this->user_id);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Manejo de error si el usuario intenta solicitar el mismo libro dos veces
            return false;
        }
    }
    
    // Obtiene las solicitudes de préstamo para un usuario específico. (VISTA DE USUARIO)
     
    public function readUserRequests(int $user_id): PDOStatement {
        $query = "
            SELECT 
                l.loan_id, l.request_date, l.status,
                b.title AS book_title, b.isbn
            FROM 
                " . $this->table_name . " l
            JOIN 
                book b ON l.book_id = b.book_id
            WHERE 
                l.user_id = :user_id
            ORDER BY 
                l.request_date DESC";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    //Obtiene todas las solicitudes para la vista del administrador.
    
    public function readAllRequests(): PDOStatement {
        $query = "
            SELECT 
                l.loan_id, l.request_date, l.status,
                b.title AS book_title,
                u.name AS user_name,
                u.last_name AS user_last_name,
                u.email AS user_email,
                u.cedula AS user_cedula
            FROM 
                " . $this->table_name . " l
            JOIN 
                book b ON l.book_id = b.book_id
            JOIN 
                users u ON l.user_id = u.user_id 
            ORDER BY 
                l.request_date DESC";
                
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    //Actualiza el estado de una solicitud (usado por el administrador).
     
    public function updateStatus(int $loan_id, string $status): bool {
        $valid_statuses = ['en_espera', 'completado', 'rechazado', 'sin_disponibilidad'];
        if (!in_array($status, $valid_statuses)) {
            return false;
        }
        
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE loan_id = :loan_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':loan_id', $loan_id);
        
        return $stmt->execute();
    }
}