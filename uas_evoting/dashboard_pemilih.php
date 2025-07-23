<?php
require_once 'templates/header.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'voter') {
    header('Location: login.php');
    exit;
}
?>

<div class="container">
    <div class="page-header">
        <h1>Selamat Datang di Laman Pemilihan</h1>
        <p>Silakan pilih kandidat Anda dengan bijak. Pilihan Anda bersifat rahasia dan tidak dapat diubah.</p>
    </div>

    <div class="candidate-list">
        <?php
        $query = "SELECT * FROM kandidat ORDER BY nomor_urut ASC";
        $result = mysqli_query($koneksi, $query);
        while ($kandidat = mysqli_fetch_assoc($result)) :
        ?>
            <div class="candidate-card">
                <div class="candidate-number"><?php echo htmlspecialchars($kandidat['nomor_urut']); ?></div>
                <img src="assets/images/<?php echo htmlspecialchars($kandidat['foto']); ?>" alt="Foto <?php echo htmlspecialchars($kandidat['nama_lengkap']); ?>" class="candidate-photo">
                <div class="candidate-info">
                    <h3 class="candidate-name"><?php echo htmlspecialchars($kandidat['nama_lengkap']); ?></h3>
                    <a href="detail_kandidat.php?id=<?php echo $kandidat['id_kandidat']; ?>" class="btn btn-secondary">Lihat Detail & Vote</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php
require_once 'templates/footer.php';
?>