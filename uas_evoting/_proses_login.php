<?php
require_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $koneksi->prepare("SELECT id_pengguna, password, role, status_memilih FROM pengguna WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($password === $user['password']) {
        if ($user['role'] === 'voter' && $user['status_memilih'] == TRUE) {
            $_SESSION['error_message'] = "Akses ditolak. Anda sudah menggunakan hak suara Anda.";
            header('Location: login.php');
            exit;
        }

        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = $user['id_pengguna'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;

    } else {
        $_SESSION['error_message'] = "Username atau Password salah.";
        header('Location: login.php');
        exit;
    }
} else {
    $_SESSION['error_message'] = "Username atau Password salah.";
    header('Location: login.php');
    exit;
}

$stmt->close();
$koneksi->close();
?>]