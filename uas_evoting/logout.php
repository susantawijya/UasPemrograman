<?php
require_once 'config/koneksi.php';

session_unset();
session_destroy();

header('Location: login.php');
exit;
?>