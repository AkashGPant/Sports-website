<?php
include 'includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : '';

$item = null;
$table = '';
$id_field = '';
$banner_field = '';

if ($id > 0 && !empty($type)) {
    if ($type === 'cricket') {
        $table = 'cricket_matches';
        $id_field = 'match_id';
        $banner_field = 'match_banner';
    } elseif ($type === 'football') {
        $table = 'football_matches';
        $id_field = 'match_id';
        $banner_field = 'match_banner';
    } elseif ($type === 'f1') {
        $table = 'f1_races';
        $id_field = 'race_id';
        $banner_field = 'race_banner';
    }

    if ($table) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE $id_field = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
    }
}

if (!$item) {
    echo "<div class='container' style='text-align:center; padding: 5rem 2rem;'><h2>Match not found.</h2><a href='index.php' class='btn btn-primary' style='margin-top: 1rem; display: inline-block;'>Back to Home</a></div>";
    include 'includes/footer.php';
    exit;
}

$banner = $item[$banner_field] ?: 'assets/images/default_banner.jpg';
$title = ($type === 'f1') ? $item['race_details'] : $item['match_details'];

// Helper to decode JSON if present or return as is
function format_detail($data) {
    if (empty($data)) return 'N/A';
    $decoded = json_decode($data);
    return ($decoded !== null) ? $decoded : $data;
}
?>

<div class="match-details-container">
    <div class="details-hero" style="background-image: url('<?php echo htmlspecialchars($banner); ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <span class="match-status status-<?php echo strtolower($item['status']); ?>"><?php echo htmlspecialchars($item['status']); ?></span>
            <p class="sport-label"><?php echo strtoupper($type); ?></p>
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <?php if ($type !== 'f1'): ?>
                <div class="teams-versus">
                    <span><?php echo htmlspecialchars($item['team_a_name']); ?></span>
                    <span class="vs-badge">VS</span>
                    <span><?php echo htmlspecialchars($item['team_b_name']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="container" style="max-width: 1000px; margin: -50px auto 4rem; position: relative; z-index: 10;">
        <div class="details-grid">
            <div class="details-main-card">
                <section class="details-section">
                    <h2>Match Description</h2>
                    <p class="description-text"><?php echo nl2br(htmlspecialchars($item['description'] ?: 'No description available for this event.')); ?></p>
                </section>

                <section class="details-section">
                    <?php if ($type === 'f1'): ?>
                        <h2>Leaderboard</h2>
                        <div class="data-box">
                            <pre><?php echo htmlspecialchars(format_detail($item['leaderboard'])); ?></pre>
                        </div>
                    <?php else: ?>
                        <h2>Scorecards</h2>
                        <div class="scorecard-grid">
                            <div class="score-card">
                                <h3><?php echo htmlspecialchars($item['team_a_name']); ?></h3>
                                <div class="data-box"><?php echo nl2br(htmlspecialchars(format_detail($item['team_a_scorecard']))); ?></div>
                            </div>
                            <div class="score-card">
                                <h3><?php echo htmlspecialchars($item['team_b_name']); ?></h3>
                                <div class="data-box"><?php echo nl2br(htmlspecialchars(format_detail($item['team_b_scorecard']))); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
            
            <div class="details-sidebar">
                <div class="info-card">
                    <h3>Event Status</h3>
                    <p class="status-indicator <?php echo strtolower($item['status']); ?>"><?php echo htmlspecialchars($item['status']); ?></p>
                    <hr style="opacity: 0.1; margin: 1rem 0;">
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Live updates and real-time statistics are provided by SSNAPP Sports Network.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
