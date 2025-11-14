<?php


function redirect(string $url): void {
    header("Location: " . $url);
    exit();
}


function isLoggedIn(): bool {
    //Se verifica 'user_id' para garantizar que el usuario está autenticado.
    return isset($_SESSION['user_id']);
}

/**
 * Verifica si el usuario actual tiene el rol de 'Admin'.
 * @return bool True si el usuario tiene el rol de 'Admin'.
 */
function isAdmin(): bool {
    //Se verifica 'role' para control de acceso basado en roles (RBAC).
    return isLoggedIn() && ($_SESSION['user_role'] === 'admin');
}

/**
 * Genera un hash seguro para la contraseña.
 * @param string 
 * @return string 
 */
function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
}