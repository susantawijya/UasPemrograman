<?php
require_once '../../config/koneksi.php';
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Anda bukan admin.");
}

function generate_password($length = 6) {
    return substr(str_shuffle("0123456789"), 0, $length);
}

if (isset($_POST['tambah_manual'])) {
    $nim = trim($_POST['nim']);
    if (empty($nim)) {
        $_SESSION['error'] = "NIM tidak boleh kosong.";
        header('Location: ../pemilih.php');
        exit;
    }

    $password_asli = generate_password();
    $password_db = $password_asli; 

    $stmt = $koneksi->prepare("INSERT INTO pengguna (username, password, role) VALUES (?, ?, 'voter')");
    $stmt->bind_param("ss", $nim, $password_db);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Pemilih dengan NIM $nim berhasil ditambahkan. Password: $password_asli";
    } else {
        $_SESSION['error'] = "Gagal menambahkan pemilih. Mungkin NIM sudah terdaftar. Error: " . $stmt->error;
    }
    $stmt->close();
    header('Location: ../pemilih.php');
    exit;
}

if (isset($_POST['tambah_csv'])) {
    if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
        $fileName = $_FILES['file_csv']['tmp_name'];
        
        $nim_passwords = [];
        $berhasil = 0;
        $gagal = 0;

        if (($handle = fopen($fileName, "r")) !== FALSE) {
            mysqli_begin_transaction($koneksi);
            try {
                $stmt = $koneksi->prepare("INSERT INTO pengguna (username, password, role) VALUES (?, ?, 'voter')");

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $nim = trim($data[0]);
                    if (empty($nim)) continue;

                    $password_asli = generate_password();
                    $password_db = $password_asli;
                    
                    $stmt->bind_param("ss", $nim, $password_db);
                    if ($stmt->execute()) {
                        $nim_passwords[] = [$nim, $password_asli];
                        $berhasil++;
                    } else {
                        $gagal++;
                    }
                }
                $stmt->close();
                mysqli_commit($koneksi);

            } catch (mysqli_sql_exception $exception) {
                mysqli_rollback($koneksi);
                $_SESSION['error'] = "Terjadi kesalahan besar saat transaksi database. Rollback dilakukan.";
                header('Location: ../pemilih.php');
                exit;
            }
            fclose($handle);
            if (!empty($nim_passwords)) {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="daftar_password_pemilih.csv"');
                $output = fopen('php://output', 'w');
                fputcsv($output, ['NIM', 'Password']);
                foreach ($nim_passwords as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
                exit;
            } else {
                 $_SESSION['error'] = "Tidak ada data pemilih baru yang berhasil diproses.";
                 header('Location: ../pemilih.php');
                 exit;
            }
        }
    } else {
        $_SESSION['error'] = "Gagal mengupload file CSV.";
        header('Location: ../pemilih.php');
        exit;
    }
}

?>