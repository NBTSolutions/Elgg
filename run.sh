#!/bin/bash

bash boot.sh
echo "Include /app/www/config/httpd/*.conf" >> /app/apache/conf/httpd.conf
chmod 777 /app/www/engine/heroku_settings.php
chmod 777 /app/www/elgg_data
chmod 777 /app/www/cache
