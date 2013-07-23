#!/bin/bash

/app/php/bin/pear channel-update pear.php.net
export LD_LIBRARY_PATH=/app/php/ext
php/bin/pear install Mail
php/bin/pear install Net_SMTP

bash boot.sh
echo "Include /app/www/config/httpd/*.conf" >> /app/apache/conf/httpd.conf
chmod 777 /app/www/engine/heroku_settings.php
chmod 777 /app/www/elgg_data
chmod 777 /app/www/cache

