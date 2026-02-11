<?php

namespace Controllers;

use Models\User;

class AuthController extends Controller {
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }

        $error = '';
        if ($this->isPost()) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $this->redirect('dashboard');
            } else {
                $error = '帳號或密碼錯誤';
            }
        }

        $this->view('auth/login', ['error' => $error]);
    }

    public function logout() {
        session_destroy();
        $this->redirect('login');
    }

    // Temporary registration helper to create the first user
    // Access via /register?u=admin&p=123456
    public function register() {
        $username = $_GET['u'] ?? 'patient';
        $password = $_GET['p'] ?? '123456';
        
        $userModel = new User();
        if ($userModel->register($username, $password)) {
            echo "User created: $username / $password. <a href='" . BASE_URL . "/login'>Login</a>";
        } else {
            echo "Failed to create user (might already exist). <a href='" . BASE_URL . "/login'>Login</a>";
        }
    }
}
