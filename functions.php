<?php
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function getStatusClass($status) {
    $classes = [
        'Pending' => 'status-Pending',
        'In Progress' => 'status-In-Progress',
        'Under Review' => 'status-Under-Review',
        'Completed' => 'status-Completed',
        'Closed' => 'status-Closed'
    ];
    return $classes[$status] ?? '';
}

function getPriorityClass($priority) {
    $classes = [
        'Low' => 'priority-Low',
        'Medium' => 'priority-Medium',
        'High' => 'priority-High',
        'Urgent' => 'priority-Urgent'
    ];
    return $classes[$priority] ?? '';
}

function getRoleBadgeClass($role) {
    $classes = [
        'super_admin' => 'bg-danger',
        'attorney' => 'bg-primary',
        'secretary' => 'bg-secondary'
    ];
    return $classes[$role] ?? 'bg-secondary';
}

function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function getStatusOptions() {
    return ['Pending', 'In Progress', 'Under Review', 'Completed', 'Closed'];
}

function getPriorityOptions() {
    return ['Low', 'Medium', 'High', 'Urgent'];
}

function getRoleOptions() {
    return ['secretary', 'attorney', 'super_admin'];
}
