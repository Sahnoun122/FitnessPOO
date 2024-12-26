<?php
require_once '../models/classes.php';
require_once '../config/db.php';

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
        $userId = $auth->register($username, $password, $firstname, $lastname, $phone, $email, $role);
        echo "Registration successful. Your User ID is: $userId";
        header('Location: login.php');
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="register.php">
        <label for="username">Username:</label><br>
        <input type="text" name="username" id="username" placeholder="Username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" placeholder="Password" required><br><br>

        <label for="firstname">First Name:</label><br>
        <input type="text" name="firstname" id="firstname" placeholder="First Name"><br><br>

        <label for="lastname">Last Name:</label><br>
        <input type="text" name="lastname" id="lastname" placeholder="Last Name"><br><br>

        <label for="phone">Phone:</label><br>
        <input type="text" name="phone" id="phone" placeholder="Phone"><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" placeholder="Email"><br><br>

        <label for="role">Role:</label><br>
        <select name="role" id="role" required>
            <option value="Member">Member</option>
            <option value="Admin">Admin</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
