<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// AUTOCARGA DE CLASES Y CONFIGURACIÓN

spl_autoload_register(function ($class) {
    $paths = ['config/', 'models/', 'controllers/'];
    foreach ($paths as $path) {
        $file = __DIR__ . '/' . $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once 'utils/functions.php'; 

$base_path = '/biblioteca'; 

$database = new Database(); 
$db = $database->getConnection();
if (!$db) {
    die("<h1>Error de Conexión a la Base de Datos.</h1>");
}

// RUTEADOR (FRONT CONTROLLER)

// Inicializar controladores
$authController = new AuthController($db);
$bookController = new BookController($db);
$authorController = new AuthorController($db);
$loanController = new LoanController($db); 

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = '/' . trim(str_replace($base_path, '', $uri), '/'); 
$method = $_SERVER['REQUEST_METHOD'];
$route_parts = explode('/', $route);


switch (true) {
    //  HOME / LIBROS ---
    case $route === '/' || $route === '/book':
        $bookController->index();
        break;

    // Autenticación 
    case $route === '/login': $authController->login($method); break;
    case $route === '/register': $authController->register($method); break;
    case $route === '/logout': $authController->logout(); break;

    // Libros (CRUD)
    case $route === '/create': 
        if (!isAdmin()) redirect($base_path . '/book');
        $bookController->create($method);
        break;
    case count($route_parts) === 3 && $route_parts[1] === 'edit' && is_numeric($route_parts[2]): 
        if (!isAdmin()) redirect($base_path . '/book');
        $id = (int)$route_parts[2];
        $bookController->edit($id, $method);
        break;
    case count($route_parts) === 3 && $route_parts[1] === 'delete' && is_numeric($route_parts[2]): 
        if (!isAdmin()) redirect($base_path . '/book');
        $id = (int)$route_parts[2];
        $bookController->delete($id);
        break;
        
    //  Autores (CRUD) 
    case $route === '/author':
        if (!isAdmin()) redirect($base_path . '/book');
        $authorController->index();
        break;
    
    case $route === '/author/create': 
        if (!isAdmin()) redirect($base_path . '/book');
        $authorController->create($method); 
        break;
    
    case count($route_parts) === 4 && $route_parts[1] === 'author' && $route_parts[2] === 'edit' && is_numeric($route_parts[3]): 
        if (!isAdmin()) redirect($base_path . '/book');
        $id = (int)$route_parts[3];
        $authorController->edit($id, $method);
        break;
    case count($route_parts) === 4 && $route_parts[1] === 'author' && $route_parts[2] === 'delete' && is_numeric($route_parts[3]): 
        if (!isAdmin()) redirect($base_path . '/book');
        $id = (int)$route_parts[3];
        $authorController->delete($id);
        break;
        
    //  Préstamos (Gestión y Solicitud) 
    case $route === '/loan':
        if (!isLoggedIn()) redirect($base_path . '/login');
        // El admin gestiona la lista (POST para actualizar estado, GET para ver lista)
        $loanController->index($method); 
        break;
        
    // Mis Préstamos (User View) 
    case $route === '/myloans':
        // No necesita verificación de Admin, solo login 
        $loanController->myLoans();
        break; 
        
    // Ruta para que el User solicite un libro: /loan/request/ID_LIBRO
    case count($route_parts) === 4 && $route_parts[1] === 'loan' && $route_parts[2] === 'request' && is_numeric($route_parts[3]): 
        if (!isLoggedIn()) redirect($base_path . '/login');
        $book_id = (int)$route_parts[3];
        $loanController->request($book_id);
        break;
        
    default:
        http_response_code(404);
        echo "<h1>404 - Página no encontrada ($route)</h1>";
        break;
}