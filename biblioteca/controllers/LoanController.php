<?php

class LoanController {
    private PDO $db;
    private Loan $loanModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->loanModel = new Loan($db); 
    }
    
    // Muestra la lista de solicitudes y procesa la actualización de estado.
     
    public function index(string $method): void {
        global $base_path;
        
        if (!isAdmin()) {
            $_SESSION['error'] = "Acceso denegado: Solo administradores pueden gestionar solicitudes.";
            redirect($base_path . '/');
            return;
        }
        
        if ($method === 'POST') {
            $loan_id = $_POST['loan_id'] ?? 0;
            $new_status = $_POST['status'] ?? '';

            if ($loan_id > 0 && !empty($new_status)) {
                if ($this->loanModel->updateStatus($loan_id, $new_status)) {
                    $_SESSION['message'] = "Estado de la solicitud actualizado.";
                } else {
                    $_SESSION['error'] = "Error al actualizar el estado o estado inválido.";
                }
            }
            redirect($base_path . '/loan');
            return;
        }
        
        $stmt = $this->loanModel->readAllRequests();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/loan/index.php'; 
    }
    
    //Muestra la lista de solicitudes de préstamo del usuario actual.
    
    public function myLoans(): void {
        global $base_path;
        
        if (!isLoggedIn()) {
            $_SESSION['error'] = "Debe iniciar sesión para ver sus préstamos.";
            redirect($base_path . '/login');
            return;
        }
        
        $user_id = $_SESSION['user_id'];
        
        $stmt = $this->loanModel->readUserRequests($user_id);
        $user_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require 'views/loan/my_loans.php'; 
    }
    
    //Procesa la solicitud de préstamo de un libro.
    
    public function request(int $book_id): void {
        global $base_path;
        
        if (!isLoggedIn() || isAdmin()) {
            $_SESSION['error'] = "Solo los usuarios registrados pueden solicitar préstamos.";
            redirect($base_path . '/');
            return;
        }

        $this->loanModel->book_id = $book_id;
        $this->loanModel->user_id = $_SESSION['user_id'];
        
        if ($this->loanModel->createRequest()) {
            $_SESSION['message'] = "Solicitud de préstamo enviada exitosamente. Revise su estado en Mis Préstamos.";
        } else {
            $_SESSION['error'] = "Error al enviar la solicitud. Puede que ya tengas una solicitud activa para este libro.";
        }
        
        redirect($base_path . '/book'); 
    }
}