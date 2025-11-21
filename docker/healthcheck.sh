#!/bin/sh
# Healthcheck pour PHP-FPM
# Vérifie que PHP-FPM répond sur le port 9000

SCRIPT_NAME=/fpm-ping \
SCRIPT_FILENAME=/fpm-ping \
REQUEST_METHOD=GET \
cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

exit 0
