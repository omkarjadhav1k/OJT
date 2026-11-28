<?php

class UserController {
    private $model;

    public function __construct() {
        $this->model = new User();
    }

    public function showLogin($error = '', $oldEmail = '', $remembered = false) {
        startSession();

        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['success']);

        if (empty($error) && !empty($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        if ($oldEmail === '' && !empty($_COOKIE['remembered_email'])) {
            $oldEmail = $_COOKIE['remembered_email'];
            $remembered = true;
        }

        $loggedInUser = null;
        if (!empty($_SESSION['user_id'])) {
            $loggedInUser = [
                'user_id'    => $_SESSION['user_id'],
                'user_name'  => $_SESSION['user_name'] ?? '',
                'user_email' => $_SESSION['user_email'] ?? '',
                'role'       => $_SESSION['user_role'] ?? 'Customer'
            ];
        }

        loadView('login', [
            'error'        => $error,
            'success'      => $success,
            'oldEmail'     => $oldEmail,
            'remembered'   => $remembered,
            'loggedInUser' => $loggedInUser
        ]);
    }

    public function login() {
        startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }

        $email    = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';
        $remember = !empty($_POST['remember_me']);

        if ($email === '' || $password === '') {
            $this->showLogin('Please enter both email and password.', $email, $remember);
            return;
        }

        $user = $this->model->verifyLogin($email, $password);

        if (!$user) {
            $this->showLogin('Invalid email or password.', $email, $remember);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id']    = $user['user_id'];
        $_SESSION['user_name']  = $user['user_name'];
        $_SESSION['user_email'] = $user['user_email'];
        $_SESSION['user_role']  = $user['role'];

        if ($remember) {
            setcookie('remembered_email', $user['user_email'], time() + (86400 * 30), '/');
        } else {
            setcookie('remembered_email', '', time() - 3600, '/');
        }

        $_SESSION['success'] = 'Welcome back, ' . $user['user_name'] . '! Login successful.';

        redirect('login');
    }

    public function logout() {
        startSession();
        $_SESSION = [];
        session_destroy();

        startSession();
        $_SESSION['success'] = 'You have been logged out successfully.';
        redirect('login');
    }

    public function getAll() {
        return $this->model->getAll();
    }

    public function register() {
        $name     = trim($_POST['user_name'] ?? '');
        $email    = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';
        $role     = $_POST['role'] ?? 'Customer';

        if ($name === '' || $email === '' || $password === '') {
            return [
                'success' => false,
                'message' => 'Name, email, and password are required.'
            ];
        }

        if ($this->model->findByEmail($email)) {
            return [
                'success' => false,
                'message' => 'Email is already registered.'
            ];
        }

        $userId = $this->model->create($name, $email, $password, $role);

        return [
            'success' => true,
            'message' => 'User registered successfully.',
            'user_id' => $userId
        ];
    }
}
