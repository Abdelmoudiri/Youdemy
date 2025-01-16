<?php
require_once __DIR__ . '/../config.php';

function checkAdmin() {
    if (!isset($_SESSION['user']) || $_SESSION['role'] !== ROLE_ADMIN) {
        header('Location: login.php');
        exit;
    }
}

function jsonResponse($success, $data = null, $message = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message
    ]);
    exit;
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generatePassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
    return substr(str_shuffle($chars), 0, $length);
}

function sendNotification($userId, $type, $message) {
    global $db;
    $stmt = $db->prepare("INSERT INTO notifications (user_id, type, message) VALUES (?, ?, ?)");
    return $stmt->execute([$userId, $type, $message]);
}

function getUnreadNotificationsCount($userId) {
    global $db;
    $stmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

function isAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
