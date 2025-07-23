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
    <title>Admin Dashboard - E-Voting</title>
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
            <a href="index.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a href="kandidat.php"><i class="fa-solid fa-user-tie"></i> Kandidat</a>
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
            <h1>Admin Dashboard</h1>
            <p>Hasil Perolehan Suara Real-Time</p>
        </div>

        <div class="content-card">
            <div class="table-responsive-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Urut</th>
                            <th>Nama Kandidat</th>
                            <th>NIM</th>
                            <th>Jumlah Suara</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT k.*, COUNT(s.id_suara) as jumlah_suara 
                                    FROM kandidat k 
                                    LEFT JOIN suara s ON k.id_kandidat = s.id_kandidat 
                                    GROUP BY k.id_kandidat 
                                    ORDER BY k.nomor_urut ASC";
                        $result = mysqli_query($koneksi, $query);
                        while ($data = mysqli_fetch_assoc($result)) :
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($data['nomor_urut']); ?></td>
                                <td><?php echo htmlspecialchars($data['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($data['nim']); ?></td>
                                <td><strong><?php echo htmlspecialchars($data['jumlah_suara']); ?></strong></td>
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