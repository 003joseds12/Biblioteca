<?php
include_once 'views/layout/header.php'; 
?>

<h2>Mis Solicitudes de Préstamo</h2>

<?php if (empty($user_requests)): ?>
    <p>Aún no has realizado ninguna solicitud de préstamo.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Libro</th>
                <th>ISBN</th>
                <th>Fecha Solicitud</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $status_map = [
                'en_espera' => 'En espera ', 
                'completado' => 'Completado ', 
                'rechazado' => 'Rechazado ', 
                'sin_disponibilidad' => 'Sin disponibilidad por el momento '
            ];
            ?>
            
            <?php foreach ($user_requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['book_title']); ?></td>
                    <td><?php echo htmlspecialchars($request['isbn']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($request['request_date'])); ?></td>
                    <td>
                        <span class="status-<?php echo htmlspecialchars($request['status']); ?>">
                            <?php echo $status_map[$request['status']] ?? $request['status']; ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
