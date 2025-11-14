<?php
include_once 'views/layout/header.php'; 
// @var array $books   La variable $books viene del controlador
global $base_path;
?>

<h2>Listado de Libros</h2>

<?php if (isAdmin()): ?>
    <a href="<?php echo $base_path; ?>/create" class="btn">Crear Nuevo Libro</a>
<?php endif; ?>

<?php if (empty($books)): ?>
    <p>No hay libros registrados en la biblioteca.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>ISBN</th>
                <th>Año Pub.</th>
                <th>Autor(es)</th>
                
                <?php if (isLoggedIn()): ?>
                    <th>Acciones</th>
                <?php endif; ?>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo htmlspecialchars($book['book_id']); ?></td>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                    <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                    <td><?php echo htmlspecialchars($book['authors'] ?? 'Desconocido'); ?></td>
                    
                    <?php if (isLoggedIn()): ?>
                        <td>
                            <?php if (isAdmin()): ?>
                                <a href="<?php echo $base_path; ?>/edit/<?php echo $book['book_id']; ?>" class="btn">Editar</a>
                                <a href="<?php echo $base_path; ?>/delete/<?php echo $book['book_id']; ?>" class="btn btn-danger">Eliminar</a>
                            <?php else: ?>
                                <a href="<?php echo $base_path; ?>/loan/request/<?php echo $book['book_id']; ?>" class="btn btn-primary">Solicitar Préstamo</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
