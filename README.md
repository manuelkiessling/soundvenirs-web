# Soundvenir Website and Backend

[![Build Status](https://travis-ci.org/manuelkiessling/soundvenir-backend.png?branch=master)](https://travis-ci.org/manuelkiessling/soundvenir-backend)

## Installation

### Requirements

* Ubuntu 12.04
* git
* php5-fpm
* php5-sqlite
* composer
* nginx

### Setup

    cd
    sudo apt-get install git nginx php5-cli php5-fpm php5-sqlite
    curl -sS https://getcomposer.org/installer | php
    sudo ln -s ~/composer.phar /usr/local/bin/composer

Set `listen` to `127.0.0.1:9001` in `/etc/php5/fpm/pool.d/www.conf`.

Create `/etc/nginx/sites-available/soundvenir.com` with the following content:

    server {
        server_name www.soundvenir.com soundvenir.com;
        listen 80;
        access_log /var/log/nginx/soundvenir.com.access.log;
        error_log /var/log/nginx/soundvenir.com.error.log;
        charset utf-8;
    
        root /opt/soundvenir.com/public;
        index index.php;
    
        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9001;
            fastcgi_index index.php;
            include fastcgi_params;
        }
    
        location / {
            if (-f $request_filename) {
                expires max;
                break;
            }
            rewrite ^(.*) /index.php last;
        }
    }

Then run

    sudo ln -s /etc/nginx/sites-available/soundvenir.com /etc/nginx/sites-enabled/
    cd /opt
    git clone git@github.com:manuelkiessling/soundvenir-backend.git ./soundvenir.com
    sudo service php5-fpm restart
    sudo service nginx restart

