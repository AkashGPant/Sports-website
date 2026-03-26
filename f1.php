<?php
include 'includes/db.php';
include 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM f1_races ORDER BY race_id DESC");
$races = $stmt->fetchAll();
?>

<div class="container" style="max-width: 1200px; margin: 4rem auto; padding: 0 2rem;">
    <h2 style="margin-bottom: 2rem; border-left: 5px solid var(--accent-orange); padding-left: 1rem;">F1 Grand Prix</h2>
    
    <div class="match-grid">
        <?php foreach ($races as $race): ?>
            <a href="match_details.php?id=<?php echo $race['race_id']; ?>&type=f1" class="match-card-link" style="text-decoration: none; color: inherit; display: block;">
                <div class="match-card">
                <div class="match-banner-container">
                    <img src="<?php echo htmlspecialchars($race['race_banner'] ?: 'https://via.placeholder.com/400x150?text=F1+Race'); ?>" alt="Banner" class="match-banner">
                    <div class="match-banner-overlay"></div>
                </div>
                <div class="match-info">
                    <span class="match-status status-<?php echo strtolower($race['status']); ?>"><?php echo htmlspecialchars($race['status']); ?></span>
                    <h3 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($race['race_details']); ?></h3>
                    <p style="color: var(--text-secondary); margin-top: 1rem; font-size: 0.9rem;">
                        <?php echo htmlspecialchars($race['description']); ?>
                    </p>
                    <?php if ($race['leaderboard']): ?>
                        <div style="margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.5rem;">
                            <p style="font-weight: bold; color: var(--accent-orange);">Live Leaderboard Available</p>
                        </div>
                    <?php endif; ?>
                </div>
                </div>
            </a>
        <?php endforeach; ?>
        
        <?php if (empty($races)): ?>
            <p style="grid-column: 1/-1; text-align: center; color: var(--text-secondary);">No F1 races currently available.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
