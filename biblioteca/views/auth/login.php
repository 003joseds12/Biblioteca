<?php
include_once 'views/layout/header.php'; 
?>
<h2>Iniciar Sesión</h2>
<form action="<?php echo $base_path; ?>/login" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit" class="btn">Acceder</button>
    <p>¿No tienes cuenta? <a href="<?php echo $base_path; ?>/register">Regístrate aquí</a></p>
</form>

<?php
