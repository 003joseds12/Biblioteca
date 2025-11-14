<?php
include_once 'views/layout/header.php'; 
// @var array $authors  La lista de autores viene del controlador
global $base_path;
?>

<h2>Gestión de Autores</h2>

<a href="<?php echo $base_path; ?>/author/create" class="btn btn-primary">Crear Nuevo Autor</a>

<?php 
// Mostrar mensajes de sesión (éxito o error)
if (isset($_SESSION['message'])): ?>
    <p class="message success"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></p>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <p class="message error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
<?php endif; ?>

<?php if (empty($authors)): ?>
    <p>Aún no hay autores registrados en el sistema.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Año Nacimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($authors as $author): ?>
                <tr>
                    <td><?php echo htmlspecialchars($author['author_id']); ?></td>
                    <td><?php echo htmlspecialchars($author['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($author['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($author['birth_year'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="<?php echo $base_path; ?>/author/edit/<?php echo $author['author_id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                        
                        <form action="<?php echo $base_path; ?>/author/delete/<?php echo $author['author_id']; ?>" method="POST" style="display:inline-block;">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar a este autor? Esto también podría afectar libros relacionados.');">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
