<?php
session_start();
require 'pdo.php';

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function isUser() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user';
}

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
}
?>
