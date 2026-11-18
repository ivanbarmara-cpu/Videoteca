<?php
declare(strict_types=1);

namespace Controllers;

use Core\Pages;
use Core\Session;
use Models\UserModel;

/**
 * Autenticación: comprueba credenciales y abre/cierra la sesión.
 * NO es el controlador de UserModel (para eso está UserController).
 */
final class AuthController
{
    // Etapa 5: leed los flash 'error' e 'info' con Session::getFlash()
    // y pasadlos a la vista (la vista no accede a Session).
    public function showLogin(): void
    {
        (new Pages())->render('auth/login', ['error' => null, 'info' => null]);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Buscamos al usuario por su email
        $user = (new UserModel())->findByEmail($email);

        // AQUÍ ESTABA EL CAMBIO: Cambiamos 'password' por 'hash'
        if ($user && isset($user['hash']) && password_verify($password, $user['hash'])) {
            Session::set('user_id', $user['id']);
            redirect('perfil');
        } else {
            redirect('login');
        }
    }

    public function logout(): void
    {
        Session::destruir();
        redirect('login');
    }
}
