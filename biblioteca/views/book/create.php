<?php
include_once 'views/layout/header.php'; 
global $base_path;
?>
<!--En esta parte es para crear nuevos libros-->

<h2>Crear Nuevo Libro</h2>

<form action="<?php echo $base_path; ?>/create" method="POST">
    <label for="title">Título:</label>
    <input type="text" id="title" name="title" required>

    <label for="isbn">ISBN (Solo números):</label>
    <input type="number" id="isbn" name="isbn" required 
           inputmode="numeric" 
           pattern="[0-9]*" 
           title="El ISBN debe contener solo números (sin guiones)">

    <label for="publication_year">Año de Publicación:</label>
    <input type="number" id="publication_year" name="publication_year" min="1000" max="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>">

    <label for="authors">Autor(es):</label>
    <select id="authors" name="author_ids[]" multiple required>
        <?php foreach ($authors as $author): ?>
            <option value="<?php echo htmlspecialchars($author['author_id']); ?>">
                <?php echo htmlspecialchars($author['last_name'] . ', ' . $author['first_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <p style="font-size: 0.9em; color: #555;">Mantener presionada la tecla Ctrl  para seleccionar múltiples autores.</p>

    <button type="submit" class="btn">Guardar Libro</button>
    <a href="<?php echo $base_path; ?>/book" class="btn btn-danger">Cancelar</a>
</form>

<?php
