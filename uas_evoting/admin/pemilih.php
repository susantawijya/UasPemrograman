<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
$path_prefix = '../';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pemilih - E-Voting</title>
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
            <a href="kandidat.php"><i class="fa-solid fa-user-tie"></i> Kandidat</a>
            <a href="pemilih.php" class="active"><i class="fa-solid fa-users"></i> Pemilih</a>
        </nav>
        <div class="admin-sidebar-footer">
            <a href="<?php echo $path_prefix; ?>logout.php" class="btn btn-nav">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="admin-main-content">
        <div class="page-header">
            <h1>Manajemen Pemilih</h1>
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

        <div class="management-grid">
            <div class="content-card">
                <h3>Tambah Pemilih Manual</h3>
                <form class="form" action="_proses/pemilih_proses.php" method="POST">
                    <div class="form-group">
                        <label for="nim_manual">NIM Pemilih</label>
                        <input type="text" id="nim_manual" name="nim" class="form-input" placeholder="Masukkan satu NIM" required>
                    </div>
                    <button type="submit" name="tambah_manual" class="btn btn-primary btn-block">Tambah</button>
                </form>
            </div>

            <div class="content-card">
                <h3>Tambah Pemilih Massal</h3>
                <form class="form" action="_proses/pemilih_proses.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="csv_file">Upload File CSV</label>
                        <input type="file" id="csv_file" name="file_csv" class="form-input" accept=".csv" required>
                        <p class="form-hint">Upload file .csv dengan satu kolom berisi daftar NIM (tanpa header).</p>
                    </div>
                    <button type="submit" name="tambah_csv" class="btn btn-primary btn-block">Upload</button>
                </form>
            </div>
        </div>

        <div class="content-card">
            <h3>Daftar Pemilih Terdaftar</h3>
            <div class="table-responsive-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Username (NIM)</th>
                            <th>Status Memilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($koneksi, "SELECT username, status_memilih FROM pengguna WHERE role='voter' ORDER BY username ASC");
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td>
                                <?php if ($row['status_memilih']): ?>
                                    <span class="status-badge status-voted">Sudah Memilih</span>
                                <?php else: ?>
                                    <span class="status-badge status-not-voted">Belum Memilih</span>
                                <?php endif; ?>
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