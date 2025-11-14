<?php

class BookController {
    private PDO $db;
    private Book $bookModel;
    private Author $authorModel; 

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->bookModel = new Book($db);
        $this->authorModel = new Author($db);
    }

    public function index(): void {
        
        $stmt = $this->bookModel->readAll(); 
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/book/index.php';
    }
    

    //aqui es para crear un libro
    public function create(string $method): void {
        global $base_path;
        $authorStmt = $this->authorModel->readAll();
        $authors = $authorStmt->fetchAll(PDO::FETCH_ASSOC);

        if ($method === 'POST') {
            if (empty($_POST['title']) || empty($_POST['isbn']) || empty($_POST['author_ids'])) {
                 $_SESSION['error'] = "El título, ISBN y al menos un autor son obligatorios.";
                 redirect($base_path . '/create');
            }
            
            $this->bookModel->title = $_POST['title'];
            $this->bookModel->isbn = preg_replace('/[^0-9]/', '', $_POST['isbn']); 
            $this->bookModel->publication_year = (int)($_POST['publication_year'] ?? date('Y'));
            $this->bookModel->author_ids = $_POST['author_ids'] ?? [];
            $this->bookModel->user_id = $_SESSION['user_id'] ?? 1; 

            if ($this->bookModel->create()) {
                $_SESSION['message'] = "Libro creado exitosamente.";
                redirect($base_path . '/book'); 
            } else {
                $_SESSION['error'] = "Error al crear el libro. El ISBN puede estar duplicado o hubo un error de base de datos.";
                redirect($base_path . '/create'); 
            }
        }
        require 'views/book/create.php';
    }
    
    public function edit(int $id, string $method): void {
        global $base_path;
        $this->bookModel->book_id = $id;
        $book = $this->bookModel->readOne();

        if (!$book) {
            $_SESSION['error'] = "Libro no encontrado.";
            redirect($base_path . '/book');
        }

        $authorStmt = $this->authorModel->readAll();
        $authors = $authorStmt->fetchAll(PDO::FETCH_ASSOC);

        if ($method === 'POST') {
            $this->bookModel->title = $_POST['title'];
            $this->bookModel->isbn = preg_replace('/[^0-9]/', '', $_POST['isbn']); 
            $this->bookModel->publication_year = (int)($_POST['publication_year'] ?? date('Y'));
            $this->bookModel->author_ids = $_POST['author_ids'] ?? [];

            if ($this->bookModel->update()) {
                $_SESSION['message'] = "Libro actualizado exitosamente.";
                redirect($base_path . '/book');
            } else {
                 $_SESSION['error'] = "Error al actualizar el libro.";
                 redirect($base_path . "/edit/{$id}");
            }
        }
        require 'views/book/edit.php';
    }

    public function delete(int $id): void {
        global $base_path;
        $this->bookModel->book_id = $id;
        if ($this->bookModel->delete()) {
            $_SESSION['message'] = "Libro eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el libro.";
        }
        redirect($base_path . '/book');
    }
}