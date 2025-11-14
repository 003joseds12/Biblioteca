<?php
include_once 'views/layout/header.php'; 
/* @var Author $author */ // El objeto Author viene del controlador
global $base_path;
?>

<h2>Editar Autor: <?php echo htmlspecialchars($author->first_name . ' ' . $author->last_name); ?></h2>

<form action="<?php echo $base_path; ?>/author/edit/<?php echo $author->author_id; ?>" method="POST">
    <label for="first_name">Nombre:</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($author->first_name); ?>" required>

    <label for="last_name">Apellido:</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($author->last_name); ?>" required>

    <label for="birth_year">Año de Nacimiento (Opcional):</label>
    <input type="number" id="birth_year" name="birth_year" min="1000" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($author->birth_year); ?>">
    
    <button type="submit" class="btn">Guardar Cambios</button>
    <a href="<?php echo $base_path; ?>/author" class="btn btn-danger">Cancelar</a>
</form>

<?php
