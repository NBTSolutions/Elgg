#!/bin/bash

bash boot.sh
echo "Include /app/www/config/httpd/*.conf" >> /app/apache/conf/httpd.conf
