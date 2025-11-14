<?php
include_once 'views/layout/header.php'; 
global $base_path;
?>

<h2>Gestión de Solicitudes de Préstamos</h2>

<?php if (isset($_SESSION['message'])): ?>
    <p class="message success"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></p>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <p class="message error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
<?php endif; ?>

<?php if (empty($requests)): ?>
    <p>No hay solicitudes de préstamos pendientes.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Libro Solicitado</th>
                <th>Usuario (Cédula)</th>
                <th>Email</th>
                <th>Fecha Solicitud</th>
                <th>Estado Actual</th>
                <th>Cambiar Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $status_map = [
                'en_espera' => 'En espera', 
                'completado' => 'Completado', 
                'rechazado' => 'Rechazado', 
                'sin_disponibilidad' => 'Sin disponibilidad de este libro por este momento'
            ];
            ?>
            
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['book_title']); ?></td>
                    <td><?php echo htmlspecialchars($request['user_name'] . ' ' . $request['user_last_name']); ?> (<?php echo htmlspecialchars($request['user_cedula']); ?>)</td>
                    <td><?php echo htmlspecialchars($request['user_email']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($request['request_date'])); ?></td>
                    
                    <td><span class="status-<?php echo htmlspecialchars($request['status']); ?>">
                        <?php echo $status_map[$request['status']] ?? $request['status']; ?>
                    </span></td>
                    
                    <td>
                        <form action="<?php echo $base_path; ?>/loan" method="POST">
                            <input type="hidden" name="loan_id" value="<?php echo $request['loan_id']; ?>">
                            
                            <select name="status" required>
                                <?php foreach ($status_map as $value => $label): ?>
                                    <option value="<?php echo $value; ?>" 
                                            <?php echo ($request['status'] == $value) ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <button type="submit" class="btn btn-sm btn-action">Actualizar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
