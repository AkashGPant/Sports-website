<?php
session_start();
require_once '../config/db.php';

function jsonOut($data){ 
    header('Content-Type: application/json'); 
    echo json_encode($data); 
    exit; 
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    
    if (!$email || !$pass) {
        jsonOut(['ok' => false, 'msg' => 'Email and password required.']);
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $userRow = $stmt->fetch();

    if ($userRow && password_verify($pass, $userRow['password_hash'])) {
        $authUser = [
            'id'       => $userRow['user_id'],
            'name'     => $userRow['username'],
            'email'    => $userRow['email'],
            'plan'     => 'Free', // Hardcoded plan for now
            'avatar'   => strtoupper(substr($userRow['username'], 0, 1)),
        ];
        $_SESSION['auth_user'] = $authUser;
        jsonOut(['ok' => true, 'user' => $authUser]);
    }
    
    // Fallback to demo account if in db it doesn't match (for demo purposes)
    if ($email === 'demo@ssnapp.com' && $pass === 'demo1234') {
        $authUser = [
            'id'       => 0,
            'name'     => 'Demo User',
            'email'    => 'demo@ssnapp.com',
            'plan'     => 'Pro',
            'avatar'   => 'D',
        ];
        $_SESSION['auth_user'] = $authUser;
        jsonOut(['ok' => true, 'user' => $authUser]);
    }

    jsonOut(['ok' => false, 'msg' => 'Invalid email or password.']);
}

if ($action === 'register') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password']   ?? '';
    
    if (!$name || !$email || !$pass)  jsonOut(['ok' => false, 'msg' => 'All fields are required.']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonOut(['ok' => false, 'msg' => 'Invalid email address.']);
    if (strlen($pass) < 6) jsonOut(['ok' => false, 'msg' => 'Password must be at least 6 characters.']);
    
    // Check if email or username already exists
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ? OR username = ?');
    $stmt->execute([$email, $name]);
    if ($stmt->fetchColumn() > 0) {
        jsonOut(['ok' => false, 'msg' => 'Email or username already registered.']);
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
    if ($stmt->execute([$name, $email, $hash])) {
        $userId = $pdo->lastInsertId();
        $authUser = [
            'id'       => $userId,
            'name'     => $name,
            'email'    => $email,
            'plan'     => 'Free',
            'avatar'   => strtoupper(substr($name, 0, 1)),
        ];
        $_SESSION['auth_user'] = $authUser;
        jsonOut(['ok' => true, 'user' => $authUser]);
    } else {
        jsonOut(['ok' => false, 'msg' => 'Registration failed. Please try again.']);
    }
}

if ($action === 'logout') {
    unset($_SESSION['auth_user']);
    jsonOut(['ok' => true]);
}

jsonOut(['ok' => false, 'msg' => 'Invalid action.']);
