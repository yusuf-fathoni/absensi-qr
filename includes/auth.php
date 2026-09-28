<?php
require_once __DIR__ . '/session.php';
security_bootstrap();
require_once __DIR__ . '/csrf.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isSiswa() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'siswa';
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: /auth/login.php");
        exit();
    }
}

function requireSiswa() {
    if (!isSiswa()) {
        header("Location: /auth/login.php");
        exit();
    }
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /auth/login.php");
        exit();
    }
}