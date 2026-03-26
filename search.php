<?php
include 'includes/db.php';
include 'includes/header.php';

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if (!empty($search_query)) {
    $q = "%$search_query%";
    
    // Search Cricket
    $stmt = $pdo->prepare("SELECT *, 'cricket' as sport FROM cricket_matches WHERE match_details LIKE ? OR team_a_name LIKE ? OR team_b_name LIKE ?");
    $stmt->execute([$q, $q, $q]);
    $results = array_merge($results, $stmt->fetchAll());
    
    // Search Football
    $stmt = $pdo->prepare("SELECT *, 'football' as sport FROM football_matches WHERE match_details LIKE ? OR team_a_name LIKE ? OR team_b_name LIKE ?");
    $stmt->execute([$q, $q, $q]);
    $results = array_merge($results, $stmt->fetchAll());
    
    // Search F1
    $stmt = $pdo->prepare("SELECT *, 'f1' as sport FROM f1_races WHERE race_details LIKE ? OR description LIKE ?");
    $stmt->execute([$q, $q]);
    $races = $stmt->fetchAll();
    // Normalize F1 results for the common loop
    foreach($races as $race) {
        $race['match_details'] = $race['race_details'];
        $race['match_banner'] = $race['race_banner'];
        $race['status'] = 'GP';
        $results[] = $race;
    }
}

?>

<div class="container" style="max-width: 1200px; margin: 4rem auto; padding: 0 2rem;">
    <h2>Search Results for: "<?php echo htmlspecialchars($search_query); ?>"</h2>
    <p style="color: var(--text-secondary); margin-bottom: 2rem;"><?php echo count($results); ?> matches found</p>
    
    <div class="match-grid">
        <?php foreach ($results as $item): ?>
            <div class="match-card">
                <img src="<?php echo htmlspecialchars($item['match_banner'] ?: 'https://via.placeholder.com/400x150?text=Sports'); ?>" alt="Banner" class="match-banner">
                <div class="match-info">
                    <span class="match-status" style="background: var(--primary-blue);"><?php echo htmlspecialchars($item['status'] ?: 'LIVE'); ?></span>
                    <p style="color: var(--accent-orange); font-size: 0.8rem; text-transform: uppercase; font-weight: bold; margin-bottom: 0.5rem;">
                        <?php echo strtoupper($item['sport']); ?>
                    </p>
                    <h3 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($item['match_details']); ?></h3>
                    <?php if (isset($item['team_a_name'])): ?>
                        <p style="color: var(--text-secondary);"><?php echo htmlspecialchars($item['team_a_name']); ?> vs <?php echo htmlspecialchars($item['team_b_name']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($results) && !empty($search_query)): ?>
            <p style="grid-column: 1/-1; text-align: center; color: var(--text-secondary);">No results found matching your search.</p>
        <?php elseif (empty($search_query)): ?>
            <p style="grid-column: 1/-1; text-align: center; color: var(--text-secondary);">Please enter a search term.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
