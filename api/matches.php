<?php
require_once '../config/db.php';

function getFlag($team) {
    $flags = [
        'India' => '🇮🇳', 'Australia' => '🇦🇺', 'Pakistan' => '🇵🇰', 'England' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿',
        'New Zealand' => '🇳🇿', 'South Africa' => '🇿🇦', 'West Indies' => '🏝️', 'Sri Lanka' => '🇱🇰',
        'Bangladesh' => '🇧🇩', 'Afghanistan' => '🇦🇫',
        'Man City' => '🔵', 'Real Madrid' => '⚪', 'Barcelona' => '🔴', 'Bayern Munich' => '🔴',
        'Arsenal' => '🔴', 'Chelsea' => '🔵', 'PSG' => '🔵', 'Inter Milan' => '⚫',
        'Liverpool' => '🔴', 'Atletico Madrid' => '🔴', 'Juventus' => '⚫', 'AC Milan' => '🔴',
        'Dortmund' => '🟡', 'Mumbai Indians' => '🔵', 'Chennai Super Kings' => '🟡'
    ];
    foreach($flags as $key => $flag) {
        if (stripos($team, $key) !== false) return $flag;
    }
    return '🏆';
}

function getAbbr($team) {
    $words = explode(' ', $team);
    if (count($words) == 1) return strtoupper(substr($team, 0, 3));
    $abbr = '';
    foreach($words as $w) $abbr .= strtoupper(substr($w, 0, 1));
    return substr($abbr, 0, 3);
}

function parseCricketScore($scorecard) {
    $scorecard = json_decode($scorecard, true) ?? $scorecard;
    if (!$scorecard || strpos(strtolower($scorecard), 'yet to bat') !== false) {
        return ['score' => '—', 'sub' => ''];
    }
    $lines = explode("\n", $scorecard);
    $firstLine = $lines[0];
    if (preg_match('/:\s*([0-9\/]+)\s*\((.*?)\)/', $firstLine, $matches)) {
        return ['score' => $matches[1], 'sub' => $matches[2] . ' Ov'];
    }
    return ['score' => $firstLine, 'sub' => ''];
}

function parseFootballScore($scorecard) {
    $scorecard = json_decode($scorecard, true) ?? $scorecard;
    if (!$scorecard) return ['score' => '—', 'sub' => ''];
    // "Real Madrid: 2 (Carvajal 74', Vinicius Jr 83')"
    if (preg_match('/:\s*([0-9]+)\s*(?:\((.*?)\))?/', $scorecard, $matches)) {
        return ['score' => $matches[1], 'sub' => $matches[2] ?? ''];
    }
    return ['score' => '—', 'sub' => ''];
}

$output = ['cricket' => [], 'football' => [], 'f1' => []];

// CRICKET
$stmt = $pdo->query('SELECT * FROM cricket_matches');
while ($row = $stmt->fetch()) {
    $homeScore = parseCricketScore($row['team_a_scorecard']);
    $awayScore = parseCricketScore($row['team_b_scorecard']);
    
    $output['cricket'][] = [
        'id' => 'c' . $row['match_id'],
        'sport' => 'cricket',
        'status' => strtolower($row['status']),
        'home' => [
            'name' => $row['team_a_name'],
            'abbr' => getAbbr($row['team_a_name']),
            'flag' => getFlag($row['team_a_name']),
            'score' => $homeScore['score'],
            'sub' => $homeScore['sub']
        ],
        'away' => [
            'name' => $row['team_b_name'],
            'abbr' => getAbbr($row['team_b_name']),
            'flag' => getFlag($row['team_b_name']),
            'score' => $awayScore['score'],
            'sub' => $awayScore['sub']
        ],
        'league' => $row['match_details'],
        'venue' => $row['description'],
        'time' => $row['status'] === 'Upcoming' ? 'Upcoming' : ($row['status'] === 'Live' ? 'LIVE' : 'Completed'),
        'pro' => false
    ];
}

// FOOTBALL
$stmt = $pdo->query('SELECT * FROM football_matches');
while ($row = $stmt->fetch()) {
    $homeScore = parseFootballScore($row['team_a_scorecard']);
    $awayScore = parseFootballScore($row['team_b_scorecard']);
    
    $output['football'][] = [
        'id' => 'f' . $row['match_id'],
        'sport' => 'football',
        'status' => strtolower($row['status']),
        'home' => [
            'name' => $row['team_a_name'],
            'abbr' => getAbbr($row['team_a_name']),
            'flag' => getFlag($row['team_a_name']),
            'score' => $homeScore['score'],
            'sub' => ''
        ],
        'away' => [
            'name' => $row['team_b_name'],
            'abbr' => getAbbr($row['team_b_name']),
            'flag' => getFlag($row['team_b_name']),
            'score' => $awayScore['score'],
            'sub' => ''
        ],
        'league' => $row['match_details'],
        'venue' => $row['description'],
        'time' => $row['status'] === 'Upcoming' ? 'Upcoming' : ($row['status'] === 'Live' ? 'LIVE' : 'Completed'),
        'pro' => false
    ];
}

// F1
$stmt = $pdo->query('SELECT * FROM f1_races');
while ($row = $stmt->fetch()) {
    // "1. Max Verstappen (Red Bull)\n2. Lando Norris (McLaren)"
    $leaderboard = json_decode($row['leaderboard'], true) ?? $row['leaderboard'];
    $lines = explode("\n", $leaderboard);
    
    $p1 = ['name' => 'TBA', 'abbr' => 'TBA', 'score' => 'P1', 'sub' => ''];
    $p2 = ['name' => 'TBA', 'abbr' => 'TBA', 'score' => 'P2', 'sub' => ''];
    
    if (isset($lines[0]) && preg_match('/[0-9]+\.\s*(.*?)\s*\((.*?)\)/', $lines[0], $m)) {
        $p1 = ['name' => $m[1], 'abbr' => getAbbr($m[1]), 'score' => 'P1', 'sub' => $m[2]];
    }
    if (isset($lines[1]) && preg_match('/[0-9]+\.\s*(.*?)\s*\((.*?)\)/', $lines[1], $m)) {
        $p2 = ['name' => $m[1], 'abbr' => getAbbr($m[1]), 'score' => 'P2', 'sub' => $m[2]];
    }
    
    $output['f1'][] = [
        'id' => 'r' . $row['race_id'],
        'sport' => 'f1',
        'status' => strtolower($row['status']),
        'home' => [
            'name' => $p1['name'],
            'abbr' => $p1['abbr'],
            'flag' => '🏎',
            'score' => $p1['score'],
            'sub' => $p1['sub']
        ],
        'away' => [
            'name' => $p2['name'],
            'abbr' => $p2['abbr'],
            'flag' => '🏎',
            'score' => $p2['score'],
            'sub' => $p2['sub']
        ],
        'league' => $row['race_details'],
        'venue' => $row['description'],
        'time' => $row['status'] === 'Upcoming' ? 'Upcoming' : ($row['status'] === 'Live' ? 'LIVE' : 'Completed'),
        'pro' => false
    ];
}

header('Content-Type: application/json');
echo json_encode($output);
