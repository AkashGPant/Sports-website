<?php
include '../includes/db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash]);
            $success = "Registration successful! You can now <a href='login.php'>Login</a>.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Username or Email already exists!";
            } else {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="form-container">
    <h2>Join SSNAPP</h2>
    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Create your account to track your watchlist and live scores.</p>
    
    <?php if ($error): ?>
        <div style="background: rgba(231, 76, 60, 0.2); padding: 1rem; border-radius: 8px; color: #e74c3c; margin-bottom: 1rem;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background: rgba(46, 204, 113, 0.2); padding: 1rem; border-radius: 8px; color: #2ecc71; margin-bottom: 1rem;">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" name="register" class="btn btn-primary" style="width: 100%;">Sign Up</button>
    </form>
    <p style="margin-top: 1.5rem; text-align: center; color: var(--text-secondary);">
        Already have an account? <a href="login.php" style="color: var(--accent-orange); text-decoration: none;">Login here</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>
