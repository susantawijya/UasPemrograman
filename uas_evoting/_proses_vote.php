<?php
require_once 'config/koneksi.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'voter') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pengguna = $_SESSION['user_id'];
    $id_kandidat = $_POST['id_kandidat'];

    mysqli_begin_transaction($koneksi);

    try {
        $stmt1 = $koneksi->prepare("INSERT INTO suara (id_pengguna, id_kandidat) VALUES (?, ?)");
        $stmt1->bind_param("ii", $id_pengguna, $id_kandidat);
        $stmt1->execute();
        $stmt1->close();

        $stmt2 = $koneksi->prepare("UPDATE pengguna SET status_memilih = TRUE WHERE id_pengguna = ?");
        $stmt2->bind_param("i", $id_pengguna);
        $stmt2->execute();
        $stmt2->close();

        mysqli_commit($koneksi);
        
        session_unset();
        session_destroy();
        
        session_start();
        $_SESSION['success_message'] = "Terima kasih, suara Anda telah berhasil direkam!";
        header('Location: login.php');
        exit;

    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($koneksi);
        
        session_unset();
        session_destroy();

        session_start();
        $_SESSION['error_message'] = "Terjadi kesalahan atau Anda sudah pernah memilih.";
        header('Location: login.php');
        exit;
    }
}
?>