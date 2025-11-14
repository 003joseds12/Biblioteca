<?php
include_once 'views/layout/header.php'; 
// @var array $book.     El array de datos del libro viene del controlador
// @var array $authors.  Todos los autores para el select vienen del controlador
global $base_path;

// Extraer los IDs de autor seleccionados para preseleccionar
$selected_author_ids = $book['author_ids'] ?? [];
?>

<h2>Editar Libro: <?php echo htmlspecialchars($book['title']); ?></h2>

<form action="<?php echo $base_path; ?>/edit/<?php echo $book['book_id']; ?>" method="POST">
    <label for="title">Título:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>

    <label for="isbn">ISBN (Solo números):</label>
    <input type="number" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>" 
           inputmode="numeric" pattern="[0-9]*" title="El ISBN debe contener solo números" required>

    <label for="publication_year">Año de Publicación:</label>
    <input type="number" id="publication_year" name="publication_year" min="1000" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($book['publication_year']); ?>">

    <label for="authors">Autor(es):</label>
    <select id="authors" name="author_ids[]" multiple required>
        <?php foreach ($authors as $author): ?>
            <?php 
            $selected = in_array($author['author_id'], $selected_author_ids) ? 'selected' : '';
            ?>
            <option value="<?php echo htmlspecialchars($author['author_id']); ?>" <?php echo $selected; ?>>
                <?php echo htmlspecialchars($author['last_name'] . ', ' . $author['first_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <p style="font-size: 0.9em; color: #555;">Mantén presionada la tecla Ctrl para seleccionar múltiples autores.</p>

    <button type="submit" class="btn">Guardar Cambios</button>
    <a href="<?php echo $base_path; ?>/book" class="btn btn-danger">Cancelar</a>
</form>

<?php
include_once 'views/layout/footer.php';