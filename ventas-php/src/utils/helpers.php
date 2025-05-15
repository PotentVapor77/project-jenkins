<?php
function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}