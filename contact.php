<?php
include 'includes/db.php';
session_start();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            $success = "Thank you for your feedback! We will get back to you soon.";
        } catch (PDOException $e) {
            // If table doesn't exist, we'll show a friendly error
            $error = "Unable to save feedback at the moment. Please ensure the 'feedback' table is created in your database.";
        }
    }
}

include 'includes/header.php';
?>

<div class="container" style="max-width: 800px; margin: 4rem auto; padding: 0 2rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Get in Touch</h1>
        <p style="color: var(--text-secondary);">Questions? Feedback? Just want to say hi? We'd love to hear from you!</p>
    </div>

    <div class="match-card" style="padding: 2.5rem;">
        <?php if ($success): ?>
            <div style="background: rgba(46, 204, 113, 0.2); padding: 1rem; border-radius: 8px; color: #2ecc71; margin-bottom: 1.5rem;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div style="background: rgba(231, 76, 60, 0.2); padding: 1rem; border-radius: 8px; color: #e74c3c; margin-bottom: 1.5rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="contact.php" method="POST">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required placeholder="John Doe">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="john@example.com">
            </div>
            <div class="form-group">
                <label for="message">Message / Feedback</label>
                <textarea id="message" name="message" rows="5" required style="width: 100%; padding: 0.8rem; background-color: var(--bg-color); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: white; outline: none; transition: var(--transition);" placeholder="What's on your mind?"></textarea>
            </div>
            <button type="submit" name="submit_feedback" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">Submit Feedback</button>
        </form>
    </div>

    <div style="margin-top: 4rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; text-align: center;">
        <div>
            <i class="fas fa-envelope" style="font-size: 2rem; color: var(--accent-orange); margin-bottom: 1rem;"></i>
            <h3>Email</h3>
            <p style="color: var(--text-secondary);">support@ssnapp.com</p>
        </div>
        <div>
            <i class="fas fa-map-marker-alt" style="font-size: 2rem; color: var(--accent-orange); margin-bottom: 1rem;"></i>
            <h3>Office</h3>
            <p style="color: var(--text-secondary);">Indore, India</p>
        </div>
        <div>
            <i class="fas fa-phone" style="font-size: 2rem; color: var(--accent-orange); margin-bottom: 1rem;"></i>
            <h3>Phone</h3>
            <p style="color: var(--text-secondary);">+91 98765 43210</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
