<?php
require_once '../models/classes.php';
require_once '../config/db.php';

session_start();

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $user = $auth->login($username, $password);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'Admin') {
            header('Location: adminDashboard.php');
        } else {
            header('Location: memberDashboard.php');
        }
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
