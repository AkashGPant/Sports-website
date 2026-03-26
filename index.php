<?php
include 'includes/db.php';
include 'includes/header.php';

// Fetch all matches for the catalogue/index
$cricket_stmt = $pdo->query("SELECT *, 'cricket' as sport FROM cricket_matches ORDER BY match_id DESC LIMIT 3");
$cricket_matches = $cricket_stmt->fetchAll();

$football_stmt = $pdo->query("SELECT *, 'football' as sport FROM football_matches ORDER BY match_id DESC LIMIT 3");
$football_matches = $football_stmt->fetchAll();

$f1_stmt = $pdo->query("SELECT *, 'f1' as sport FROM f1_races ORDER BY race_id DESC LIMIT 3");
$f1_races = $f1_stmt->fetchAll();

$all_items = array_merge($cricket_matches, $football_matches, $f1_races);
// Sort by ID or status if needed, but for now just show them
?>

<section class="hero">
    <h1>Experience Sports Like Never Before</h1>
    <p>Live Scores, Highlights, and Exclusive Content for Cricket, Football, and F1.</p>
    
    <div class="search-container">
        <form action="search.php" method="GET">
            <input type="text" name="q" class="search-input" placeholder="Search for matches, teams, or players...">
            <button type="submit" style="display:none;"></button>
        </form>
    </div>
</section>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <h2 style="margin-bottom: 2rem; border-left: 5px solid var(--accent-orange); padding-left: 1rem;">Live & Upcoming</h2>
    
    <div class="match-grid">
        <?php foreach ($all_items as $item): ?>
            <a href="match_details.php?id=<?php echo $item['match_id'] ?? $item['race_id']; ?>&type=<?php echo $item['sport']; ?>" class="match-card-link" style="text-decoration: none; color: inherit; display: block;">
                <div class="match-card">
                <?php 
                    $banner = !empty($item['match_banner']) ? $item['match_banner'] : (!empty($item['race_banner']) ? $item['race_banner'] : 'assets/images/default_banner.jpg');
                    $details = !empty($item['match_details']) ? $item['match_details'] : $item['race_details'];
                    $status_class = 'status-' . strtolower($item['status']);
                ?>
                <div class="match-banner-container">
                    <img src="<?php echo htmlspecialchars($banner); ?>" alt="Banner" class="match-banner" onerror="this.src='https://via.placeholder.com/400x150?text=Sports+Match';">
                    <div class="match-banner-overlay"></div>
                </div>
                <div class="match-info">
                    <span class="match-status <?php echo $status_class; ?>"><?php echo htmlspecialchars($item['status']); ?></span>
                    <p style="color: var(--accent-orange); font-size: 0.8rem; text-transform: uppercase; font-weight: bold; margin-bottom: 0.5rem;">
                        <?php echo strtoupper($item['sport']); ?>
                    </p>
                    <h3 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($details); ?></h3>
                    <?php if ($item['sport'] != 'f1'): ?>
                        <p style="color: var(--text-secondary);"><?php echo htmlspecialchars($item['team_a_name']); ?> vs <?php echo htmlspecialchars($item['team_b_name']); ?></p>
                    <?php endif; ?>
                </div>
                </div>
            </a>
        <?php endforeach; ?>
        
        <?php if (empty($all_items)): ?>
            <p style="grid-column: 1/-1; text-align: center; color: var(--text-secondary);">No matches found in the database. Please add some data via phpMyAdmin.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
