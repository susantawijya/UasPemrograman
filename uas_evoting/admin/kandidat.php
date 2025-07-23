<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
$path_prefix = '../';

$is_edit_mode = false;
$kandidat_data = [
    'id_kandidat' => '', 'nomor_urut' => '', 'nama_lengkap' => '', 'nim' => '',
    'fakultas' => '', 'program_studi' => '', 'foto' => '', 'visi' => '', 'misi' => ''
];
if (isset($_GET['edit_id'])) {
    $is_edit_mode = true;
    $id_to_edit = intval($_GET['edit_id']);
    $stmt = $koneksi->prepare("SELECT * FROM kandidat WHERE id_kandidat = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $kandidat_data = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kandidat - E-Voting</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="admin-page-body">

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h1 class="admin-sidebar-title">E-VOTING</h1>
        <nav class="admin-nav">
            <a href="index.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a href="kandidat.php" class="active"><i class="fa-solid fa-user-tie"></i> Kandidat</a>
            <a href="pemilih.php"><i class="fa-solid fa-users"></i> Pemilih</a>
        </nav>
        <div class="admin-sidebar-footer">
            <a href="<?php echo $path_prefix; ?>logout.php" class="btn btn-nav">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="admin-main-content">
        <div class="page-header">
            <h1>Manajemen Kandidat</h1>
        </div>
        
        <?php
        if (isset($_SESSION['message'])) {
            echo '<p class="message success">' . $_SESSION['message'] . '</p>';
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo '<p class="message error">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <div class="content-card">
            <h3><?php echo $is_edit_mode ? 'Edit Kandidat' : 'Tambah Kandidat Baru'; ?></h3>
            <form class="form" action="_proses/kandidat_proses.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_kandidat" value="<?php echo $kandidat_data['id_kandidat']; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nomor Urut</label>
                        <input type="number" name="nomor_urut" class="form-input" value="<?php echo htmlspecialchars($kandidat_data['nomor_urut']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-input" value="<?php echo htmlspecialchars($kandidat_data['nama_lengkap']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" class="form-input" value="<?php echo htmlspecialchars($kandidat_data['nim']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Fakultas</label>
                        <input type="text" name="fakultas" class="form-input" value="<?php echo htmlspecialchars($kandidat_data['fakultas']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <input type="text" name="program_studi" class="form-input" value="<?php echo htmlspecialchars($kandidat_data['program_studi']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-input" <?php echo !$is_edit_mode ? 'required' : ''; ?>>
                        <?php if ($is_edit_mode && $kandidat_data['foto']): ?>
                            <p class="current-file-info">Foto saat ini: <?php echo htmlspecialchars($kandidat_data['foto']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group form-full-width">
                        <label>Visi</label>
                        <textarea name="visi" rows="4" class="form-input" required><?php echo htmlspecialchars($kandidat_data['visi']); ?></textarea>
                    </div>
                    <div class="form-group form-full-width">
                        <label>Misi</label>
                        <textarea name="misi" rows="6" class="form-input" required><?php echo htmlspecialchars($kandidat_data['misi']); ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <?php if ($is_edit_mode): ?>
                        <button type="submit" name="update_kandidat" class="btn btn-primary">Update Kandidat</button>
                        <a href="kandidat.php" class="btn btn-secondary">Batal Edit</a>
                    <?php else: ?>
                        <button type="submit" name="tambah_kandidat" class="btn btn-primary">Tambah Kandidat</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="content-card">
            <h3>Daftar Kandidat</h3>
            <div class="table-responsive-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($koneksi, "SELECT * FROM kandidat ORDER BY nomor_urut ASC");
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nomor_urut']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?php echo htmlspecialchars($row['nim']); ?></td>
                            <td class="table-actions">
                                <a href="kandidat.php?edit_id=<?php echo $row['id_kandidat']; ?>" class="action-link edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                <a href="_proses/kandidat_proses.php?hapus_id=<?php echo $row['id_kandidat']; ?>" class="action-link delete" onclick="return confirm('Yakin ingin menghapus kandidat ini?')"><i class="fa-solid fa-trash-can"></i> Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>