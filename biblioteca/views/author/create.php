<?php
include_once 'views/layout/header.php'; 
global $base_path;
?>

<h2>Crear Nuevo Autor</h2>

<?php 
// Mostrar mensajes de sesión (éxito o error)
if (isset($_SESSION['error'])): ?>
    <p class="message error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
<?php endif; ?>

<form action="<?php echo $base_path; ?>/author/create" method="POST">
    <label for="first_name">Nombre:</label>
    <input type="text" id="first_name" name="first_name" required>

    <label for="last_name">Apellido:</label>
    <input type="text" id="last_name" name="last_name" required>

    <label for="birth_year">Año de Nacimiento (Opcional):</label>
    <input type="number" id="birth_year" name="birth_year" min="1000" max="<?php echo date('Y'); ?>">
    
    <button type="submit" class="btn btn-primary">Guardar Autor</button>
    <a href="<?php echo $base_path; ?>/author" class="btn btn-danger">Cancelar</a>
</form>

<?php
