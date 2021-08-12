<?php

require 'autoload.php';

$stats = new GameLeaderboard;
$render = 'render' . strtoupper(
    $_GET['format'] ?? 0 ?: 'json'
);

if (method_exists($stats, $render)) {
    $stats->$render();
} else {
    http_response_code(400);
}
