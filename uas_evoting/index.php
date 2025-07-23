<?php
require_once 'config/koneksi.php';

if (!isset($_SESSION['is_logged_in'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] === 'admin') {
    header('Location: admin/index.php');
    exit;
} elseif ($_SESSION['role'] === 'voter') {
    header('Location: dashboard_pemilih.php');
    exit;
}
?>