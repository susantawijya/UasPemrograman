<?php
require_once 'templates/header.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'voter') {
    header('Location: login.php');
    exit;
}

$id_kandidat = intval($_GET['id']);
$stmt = $koneksi->prepare("SELECT * FROM kandidat WHERE id_kandidat = ?");
$stmt->bind_param("i", $id_kandidat);
$stmt->execute();
$result = $stmt->get_result();
$kandidat = $result->fetch_assoc();
$stmt->close();
?>

<div class="container">
    <div class="page-header">
        <a href="dashboard_pemilih.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kandidat</a>
        <h1>Detail Kandidat</h1>
    </div>

    <div class="candidate-detail-card">
        <div class="candidate-detail-header">
            <img src="assets/images/<?php echo htmlspecialchars($kandidat['foto']); ?>" alt="Foto <?php echo htmlspecialchars($kandidat['nama_lengkap']); ?>" class="candidate-detail-photo">
            <div class="candidate-detail-main-info">
                <h2 class="candidate-detail-name"><?php echo htmlspecialchars($kandidat['nama_lengkap']); ?></h2>
                <p><strong>Nomor Urut:</strong> <?php echo htmlspecialchars($kandidat['nomor_urut']); ?></p>
                <p><strong>NIM:</strong> <?php echo htmlspecialchars($kandidat['nim']); ?></p>
                <p><strong>Fakultas:</strong> <?php echo htmlspecialchars($kandidat['fakultas']); ?></p>
                <p><strong>Program Studi:</strong> <?php echo htmlspecialchars($kandidat['program_studi']); ?></p>
            </div>
        </div>

        <div class="vision-mission-section">
            <h3>Visi</h3>
            <p><?php echo nl2br(htmlspecialchars($kandidat['visi'])); ?></p>

            <h3>Misi</h3>
            <p><?php echo nl2br(htmlspecialchars($kandidat['misi'])); ?></p>
        </div>

        <div class="vote-section">
            <form action="_proses_vote.php" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin memilih kandidat ini? Pilihan Anda tidak dapat diubah kembali.')">
                <input type="hidden" name="id_kandidat" value="<?php echo $kandidat['id_kandidat']; ?>">
                <button type="submit" class="btn btn-vote"><i class="fa-solid fa-check-to-slot"></i> VOTE KANDIDAT INI</button>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'templates/footer.php';
?>