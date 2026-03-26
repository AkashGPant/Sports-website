<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSNAPP - Live Sports & Scores</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous" onerror="this.remove()"></script>
</head>
<body>
    <header>
        <nav class="nav-container">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>index.php">
                    <!-- Assuming logo.png exists, using text as fallback -->
                    <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="SSNAPP" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <span style="display:none; color: var(--accent-orange); font-size: 1.5rem; font-weight: bold;">SSNAPP</span>
                </a>
            </div>
            <ul class="nav-menu">
                <li><a href="<?php echo BASE_URL; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>cricket.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'cricket.php' ? 'active' : ''; ?>">Cricket</a></li>
                <li><a href="<?php echo BASE_URL; ?>football.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'football.php' ? 'active' : ''; ?>">Football</a></li>
                <li><a href="<?php echo BASE_URL; ?>f1.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'f1.php' ? 'active' : ''; ?>">F1</a></li>
                <li><a href="<?php echo BASE_URL; ?>contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
            </ul>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="<?php echo BASE_URL; ?>auth/logout.php" class="btn btn-secondary">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>auth/login.php" class="btn btn-secondary">Login</a>
                    <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>
    <main>
