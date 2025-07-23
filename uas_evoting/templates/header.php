<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';
$path_prefix = strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? '../' : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voting Ma Chung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">

    <header class="site-header">
        <div class="container header-content">
            <h1 class="site-title">
                <a href="<?php echo $path_prefix; ?>index.php">E-Voting Ma Chung</a>
            </h1>
            
            <?php if (isset($_SESSION['is_logged_in'])): ?>
            <nav class="main-nav">
                <ul>
                    <?php if (isset($_SESSION['username'])): ?>
                    <li>
                        <span class="welcome-user">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo $path_prefix; ?>logout.php" class="btn btn-nav">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </header>

    <main class="main-content">