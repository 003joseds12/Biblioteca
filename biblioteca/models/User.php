<?php

class User {
    private PDO $conn;
    private string $table_name = "users";

    public function __construct(PDO $db) { $this->conn = $db; }

    public function register(string $name, string $lastName, string $cedula, string $email, string $phone, string $password): bool {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, last_name=:last_name, cedula=:cedula, 
                      email=:email, phone=:phone, password=:password, user_role='user'"; 

        $stmt = $this->conn->prepare($query);

        // Sanitización y Hash
        $name = htmlspecialchars(strip_tags($name));
        $lastName = htmlspecialchars(strip_tags($lastName));
        $cedula = htmlspecialchars(strip_tags($cedula));
        $email = htmlspecialchars(strip_tags($email));
        $phone = htmlspecialchars(strip_tags($phone));
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Bind de parámetros
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $password_hash);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            
            // CÓDIGO DE DEPURACIÓN TEMPORAL 
            // Al ejecutar esta parte del codigo ESTO buscara en el SISTEMA SE DETENDRÁ PARA MOSTRAR EL ERROR REAL DE LA BASE DE DATOS 
            echo "<h2>ERROR DE BASE DE DATOS (DEBUG):</h2>";
            echo "SQLSTATE Code: " . $e->getCode() . "<br>";
            echo "Mensaje de MySQL: " . $e->getMessage() . "<br>";
            die("Detenido para debug. Después de ver el error, elimina estas líneas de models/User.php.");
            
            return false;
        }
    }
    
   
    public function login(string $email, string $password): bool {
        $query = "SELECT user_id, password, user_role FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_role'] = $row['user_role'];
                return true;
            }
        }
        return false;
    }
}