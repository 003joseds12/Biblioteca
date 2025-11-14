<?php
include_once 'views/layout/header.php'; 
global $base_path;
?>

<h2>Crear Cuenta</h2>

<?php if (isset($_SESSION['error'])): ?>
    <p class="message error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
<?php endif; ?>

<form action="<?php echo $base_path; ?>/register" method="POST">
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name" required>

    <label for="last_name">Apellido:</label>
    <input type="text" id="last_name" name="last_name" required>
    
    <label for="cedula">Cédula:</label>
    <input type="text" id="cedula" name="cedula" inputmode="numeric" pattern="[0-9]*" required>

    <label for="email">Correo:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="phone">Teléfono (Tlf):</label>
    <input type="tel" id="phone" name="phone" required>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>
    
    <label for="confirm_password">Confirmar Contraseña:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <button type="submit" class="btn btn-primary">Registrarse</button>
    <p>¿Ya tienes cuenta? <a href="<?php echo $base_path; ?>/login">Inicia Sesión aquí</a></p>
</form>

<?php
