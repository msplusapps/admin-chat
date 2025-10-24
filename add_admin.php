<?php
require_once 'database.php';

$username = 'admin';
$password = password_hash('password', PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$stmt->execute();

echo "Admin user created successfully.";
?>
