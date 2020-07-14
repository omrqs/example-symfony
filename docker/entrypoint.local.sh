#!/bin/sh

# Running composer and cleanup cache.
php /usr/local/bin/composer setup
php /usr/local/bin/composer server-start
