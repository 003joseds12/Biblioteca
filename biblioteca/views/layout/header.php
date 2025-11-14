<?php
global $base_path;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J.A.N - Gestión de Biblioteca</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .header { background: #333; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; margin: 0 10px; text-decoration: none; }
        .container { padding: 20px; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px 0; }
        .btn-danger { background-color: #dc3545; }
        .btn:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <div class="header">
        <a href="<?php echo $base_path; ?>/book">J.A.N Biblioteca</a>
        <nav>
            <?php if (isLoggedIn()): ?>
                <a href="<?php echo $base_path; ?>/book">Libros</a>
                
                <?php if (isAdmin()): ?>
                    <a href="<?php echo $base_path; ?>/create">Crear Nuevo Libro</a>
                    <a href="<?php echo $base_path; ?>/author">Autores</a>
                    <a href="<?php echo $base_path; ?>/loan">Solicitudes de Préstamo</a>
                <?php else: ?>
                    <a href="<?php echo $base_path; ?>/myloans">Mis Préstamos</a>
                <?php endif; ?>
                
                <span>Hola, <?php echo $_SESSION['email'] ?? 'Usuario'; ?> (<?php echo $_SESSION['user_role'] ?? ''; ?>)</span>
                <a href="<?php echo $base_path; ?>/logout">Cerrar Sesión</a>
            <?php else: ?>
                <a href="<?php echo $base_path; ?>/login">Iniciar Sesión</a>
                <a href="<?php echo $base_path; ?>/register">Registro</a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="container">
        <?php 
        // Muestra mensajes de éxito o error
        if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>