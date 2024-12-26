<?php
require_once '../models/classes.php';
require_once '../config/db.php';

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Member') {
    header("Location: login.php");
    exit;
}
?>