<?php
require_once '../../config/koneksi.php';
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Anda bukan admin.");
}

function upload_foto($file) {
    $namaFile = $file['name'];
    $ukuranFile = $file['size'];
    $error = $file['error'];
    $tmpName = $file['tmp_name'];

    if ($error === 4) {
        return null; 
    }

    $ekstensiValid = ['jpg', 'jpeg', 'png'];
    $ekstensi = explode('.', $namaFile);
    $ekstensi = strtolower(end($ekstensi));
    if (!in_array($ekstensi, $ekstensiValid)) {
        $_SESSION['error'] = "Format file tidak valid! (Hanya jpg, jpeg, png)";
        return false;
    }

    $namaFileBaru = uniqid() . '.' . $ekstensi;

    move_uploaded_file($tmpName, '../../assets/images/' . $namaFileBaru);
    return $namaFileBaru;
}


if (isset($_POST['tambah_kandidat'])) {
    $nomor_urut = $_POST['nomor_urut'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $nim = $_POST['nim'];
    $fakultas = $_POST['fakultas'];
    $program_studi = $_POST['program_studi'];
    $visi = $_POST['visi'];
    $misi = $_POST['misi'];

    $foto = upload_foto($_FILES['foto']);
    if ($foto === false) { 
        header('Location: ../kandidat.php');
        exit;
    }

    $stmt = $koneksi->prepare("INSERT INTO kandidat (nomor_urut, nama_lengkap, nim, fakultas, program_studi, foto, visi, misi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $nomor_urut, $nama_lengkap, $nim, $fakultas, $program_studi, $foto, $visi, $misi);
    
    if($stmt->execute()) {
        $_SESSION['message'] = "Kandidat berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan kandidat: " . $stmt->error;
    }
    $stmt->close();
    header('Location: ../kandidat.php');
    exit;
}

if (isset($_POST['update_kandidat'])) {
    $id_kandidat = $_POST['id_kandidat'];
    $nomor_urut = $_POST['nomor_urut'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $nim = $_POST['nim'];
    $fakultas = $_POST['fakultas'];
    $program_studi = $_POST['program_studi'];
    $visi = $_POST['visi'];
    $misi = $_POST['misi'];

    $foto_baru = upload_foto($_FILES['foto']);

    if ($foto_baru === false) { 
        header('Location: ../kandidat.php');
        exit;
    }

    if ($foto_baru) {

        $stmt = $koneksi->prepare("UPDATE kandidat SET nomor_urut=?, nama_lengkap=?, nim=?, fakultas=?, program_studi=?, foto=?, visi=?, misi=? WHERE id_kandidat=?");
        $stmt->bind_param("isssssssi", $nomor_urut, $nama_lengkap, $nim, $fakultas, $program_studi, $foto_baru, $visi, $misi, $id_kandidat);
    } else {
        $stmt = $koneksi->prepare("UPDATE kandidat SET nomor_urut=?, nama_lengkap=?, nim=?, fakultas=?, program_studi=?, visi=?, misi=? WHERE id_kandidat=?");
        $stmt->bind_param("issssssi", $nomor_urut, $nama_lengkap, $nim, $fakultas, $program_studi, $visi, $misi, $id_kandidat);
    }

    if($stmt->execute()) {
        $_SESSION['message'] = "Kandidat berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate kandidat: " . $stmt->error;
    }
    $stmt->close();
    header('Location: ../kandidat.php');
    exit;
}

if (isset($_GET['hapus_id'])) {
    $id_to_delete = intval($_GET['hapus_id']);
    $stmt_select = $koneksi->prepare("SELECT foto FROM kandidat WHERE id_kandidat = ?");
    $stmt_select->bind_param("i", $id_to_delete);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if($data = $result->fetch_assoc()){
        if(file_exists('../../assets/images/' . $data['foto'])){
            unlink('../../assets/images/' . $data['foto']);
        }
    }
    $stmt_select->close();
    $stmt_delete = $koneksi->prepare("DELETE FROM kandidat WHERE id_kandidat = ?");
    $stmt_delete->bind_param("i", $id_to_delete);
    if($stmt_delete->execute()){
        $_SESSION['message'] = "Kandidat berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus kandidat.";
    }
    $stmt_delete->close();
    header('Location: ../kandidat.php');
    exit;
}

?>