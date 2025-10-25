<?php
// Use __DIR__ to ensure the path is always correct.
require_once __DIR__ . '/../../database.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        try {
            $stmt = $db->prepare("SELECT id, password FROM admins WHERE username = :username");
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                // Session is started in login.php, no need to start it here.
                $_SESSION['admin_id'] = $admin['id'];
                header('Location: /admin');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            // In a real app, you would log this error.
            $error = 'A database error occurred. Please try again later.';
        }
    }
}
