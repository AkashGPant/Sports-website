<?php
include 'includes/db.php';
include 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM football_matches ORDER BY match_id DESC");
$matches = $stmt->fetchAll();
?>

<div class="container" style="max-width: 1200px; margin: 4rem auto; padding: 0 2rem;">
    <h2 style="margin-bottom: 2rem; border-left: 5px solid var(--accent-orange); padding-left: 1rem;">Football Matches</h2>
    
    <div class="match-grid">
        <?php foreach ($matches as $match): ?>
            <a href="match_details.php?id=<?php echo $match['match_id']; ?>&type=football" class="match-card-link" style="text-decoration: none; color: inherit; display: block;">
                <div class="match-card">
                <div class="match-banner-container">
                    <img src="<?php echo htmlspecialchars($match['match_banner'] ?: 'https://via.placeholder.com/400x150?text=Football'); ?>" alt="Banner" class="match-banner">
                    <div class="match-banner-overlay"></div>
                </div>
                <div class="match-info">
                    <span class="match-status status-<?php echo strtolower($match['status']); ?>"><?php echo htmlspecialchars($match['status']); ?></span>
                    <h3 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($match['match_details']); ?></h3>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                        <div style="text-align: center;">
                            <p style="font-weight: bold;"><?php echo htmlspecialchars($match['team_a_name']); ?></p>
                        </div>
                        <div style="color: var(--accent-orange); font-weight: bold;">VS</div>
                        <div style="text-align: center;">
                            <p style="font-weight: bold;"><?php echo htmlspecialchars($match['team_b_name']); ?></p>
                        </div>
                    </div>
                </div>
                </div>
            </a>
        <?php endforeach; ?>
        
        <?php if (empty($matches)): ?>
            <p style="grid-column: 1/-1; text-align: center; color: var(--text-secondary);">No football matches currently available.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
