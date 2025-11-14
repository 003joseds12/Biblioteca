<?php

class AuthController {
    private PDO $db;
    private User $userModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->userModel = new User($db); 
        
    }

    public function login(string $method): void {
        global $base_path;
        if ($method === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            if ($this->userModel->login($email, $password)) {
                $_SESSION['message'] = "¡Bienvenido!";
                redirect($base_path . '/');
            } else {
                $_SESSION['error'] = "Email o contraseña incorrectos.";
                redirect($base_path . '/login');
            }
            return;
        }
        require 'views/auth/login.php';
    }

    public function register(string $method): void {
        global $base_path;
        if ($method === 'POST') {
            $name = $_POST['name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $cedula = $_POST['cedula'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($email) || empty($password) || $password !== $confirmPassword) {
                $_SESSION['error'] = "Verifica que todos los campos estén llenos y las contraseñas coincidan.";
                redirect($base_path . '/register');
                return;
            }
            
            if ($this->userModel->register($name, $lastName, $cedula, $email, $phone, $password)) {
                $_SESSION['message'] = "Registro exitoso. Por favor, inicia sesión.";
                redirect($base_path . '/login');
            } else {
                // Mensaje genérico que se muestra si el modelo devuelve false (por unicidad o NOT NULL)
                $_SESSION['error'] = "Error al registrar. El email o la cédula ya están en uso.";
                redirect($base_path . '/register');
            }
            return;
        }
        require 'views/auth/register.php';
    }

    public function logout(): void {
        global $base_path;
        session_destroy();
        redirect($base_path . '/login');
    }
}