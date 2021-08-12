#!/bin/sh
(crontab -l; cat /var/www/game-cron) | crontab -
php-fpm
crond -l 2 -d