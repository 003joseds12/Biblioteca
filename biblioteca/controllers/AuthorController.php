<?php


class AuthorController {
    private PDO $db;
    private Author $authorModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->authorModel = new Author($db);
    }
    
    public function index(): void {
        $stmt = $this->authorModel->readAll();
        $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/author/index.php';
    }

    public function create(string $method): void {
        global $base_path;
        if ($method === 'POST') {
            $this->authorModel->first_name = $_POST['first_name'] ?? '';
            $this->authorModel->last_name = $_POST['last_name'] ?? '';
            $this->authorModel->birth_year = (int)($_POST['birth_year'] ?? null);

            if ($this->authorModel->create()) {
                $_SESSION['message'] = "Autor creado exitosamente.";
                redirect($base_path . '/author');
            } else {
                $_SESSION['error'] = "Error al crear el autor.";
                redirect($base_path . '/author/create'); 
            }
        }
        require 'views/author/create.php';
    }

    public function edit(int $id, string $method): void {
        global $base_path;
        $this->authorModel->author_id = $id;
        $author = $this->authorModel->readOne(); 

        if (!$author) {
            $_SESSION['error'] = "Autor no encontrado.";
            redirect($base_path . '/author');
        }

        if ($method === 'POST') {
            $this->authorModel->first_name = $_POST['first_name'] ?? '';
            $this->authorModel->last_name = $_POST['last_name'] ?? '';
            $this->authorModel->birth_year = (int)($_POST['birth_year'] ?? null);

            if ($this->authorModel->update()) {
                $_SESSION['message'] = "Autor actualizado exitosamente.";
                redirect($base_path . '/author');
            } else {
                 $_SESSION['error'] = "Error al actualizar el autor.";
                 redirect($base_path . "/author/edit/{$id}");
            }
        }
        $author = $this->authorModel; 
        require 'views/author/edit.php'; 
    }

    public function delete(int $id): void {
        global $base_path;
        $this->authorModel->author_id = $id;
        if ($this->authorModel->delete()) {
            $_SESSION['message'] = "Autor eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el autor.";
        }
        redirect($base_path . '/author');
    }
}