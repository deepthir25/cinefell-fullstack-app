<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Authentication required']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method']);
}

$movieId = (int)($_POST['movie_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($movieId <= 0) {
    jsonResponse(['success' => false, 'message' => 'Invalid movie ID']);
}

try {
    if ($action === 'add') {
        // Check if already favorited
        $stmt = $pdo->prepare("SELECT favorite_id FROM favorites WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$_SESSION['user_id'], $movieId]);
        
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO favorites (user_id, movie_id) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $movieId]);
        }
        
        jsonResponse(['success' => true, 'action' => 'added']);
    } 
    elseif ($action === 'remove') {
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$_SESSION['user_id'], $movieId]);
        
        jsonResponse(['success' => true, 'action' => 'removed']);
    } 
    else {
        jsonResponse(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}