<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/koneksi.php';

if (isset($_SESSION['is_logged_in'])) {
    $dashboard_url = ($_SESSION['role'] === 'admin') ? 'admin/index.php' : 'dashboard_pemilih.php';
    header('Location: ' . $dashboard_url);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Voting Ma Chung</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="login-page-body">

<div class="page-wrapper">
    <header class="site-header">
        <div class="container header-content">
            <h1 class="site-title">
                <a href="index.php">E-Voting Ma Chung</a>
            </h1>
        </div>
    </header>
    <main class="main-content">
        <div class="login-page-container">
            <div class="login-card">
                <h1>Login Sistem</h1>
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo '<p class="message error">' . $_SESSION['error_message'] . '</p>';
                    unset($_SESSION['error_message']);
                }
                if (isset($_SESSION['success_message'])) {
                    echo '<p class="message success">' . $_SESSION['success_message'] . '</p>';
                    unset($_SESSION['success_message']);
                }
                ?>
                <form class="form" action="_proses_login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username (NIM)</label>
                        <input type="text" id="username" name="username" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </main>
    <footer class="site-footer">
        <p>&copy; <?php echo date('Y'); ?> Gede Susanta Wijaya (322410006). All Rights Reserved.</p>
    </footer>
</div>
</body>
</html>