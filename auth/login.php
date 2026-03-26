<?php
include '../includes/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}

include '../includes/header.php';
?>

<div class="form-container">
    <h2>Welcome Back</h2>
    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Login to your SSNAPP account.</p>
    
    <?php if ($error): ?>
        <div style="background: rgba(231, 76, 60, 0.2); padding: 1rem; border-radius: 8px; color: #e74c3c; margin-bottom: 1rem;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary" style="width: 100%;">Login</button>
    </form>
    <p style="margin-top: 1.5rem; text-align: center; color: var(--text-secondary);">
        Don't have an account? <a href="register.php" style="color: var(--accent-orange); text-decoration: none;">Sign up here</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>
