<?php

if (
    ($cli = php_sapi_name() == 'cli')
    || isset($_GET['raw'])
) {
    require '/var/www/html/autoload.php';

    $game = new Gameplay;
    $game->verbose = true;

    if ($cli) echo "\033[32m";
    $game->play();
    if ($cli) echo "\033[0m";

    exit(0);
} else {
    http_response_code(403);
}
