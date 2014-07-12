# Soundvenirs Homepage, Web App, and Webservice API

[![Build Status](https://travis-ci.org/manuelkiessling/soundvenirs-backend.png?branch=master)](https://travis-ci.org/manuelkiessling/soundvenirs-backend)


## Architecture

### The big picture

                 +------------+ +---------------------------+
                 |            | |                           |
                 |            | |         Web App           |
                 |            | |                           |
                 |            | +---------------------------+
                 |  Homepage  |                              
                 |            | +---------------------------+
    +------------+            | |                           |
    |            |            | |      Webservice API       |
    |  +--------->            | |                           |
    |  |         +------+-----+ +--------------+------------+
    |  |                |                      |             
    |  |         +------v----------------------v------------+
    |  |         |                                          |
    |  |         |                 Domain                   |
    |  |         |                                          |
    |  |         +---------+----^------------+----^---------+
    |  |                   |    |            |    |          
    |  |              +----v----+----+  +----v----+----+     
    |  |              |              |  |              |     
    |  +--------------+              |  |              |     
    |                 |  File Store  |  |   Database   |     
    +----------------->              |  |              |     
                      |              |  |              |     
                      +--------------+  +--------------+     

### Components of the architecture

This repository contains the code for the Soundvenirs homepage, the web application, the webservice API, and the
centralized domain (or "business") logic:

    Component         In Module                        Accessible at
    ----------------------------------------------------------------
    Homepage          Soundvenirs\HomepageBundle       /
    Web App           Soundvenirs\WebappBundle         /app/#/
    Webservice API    Soundvenirs\ApiBundle            /api/
    Domain            Soundvenirs\DomainBundle         -

The framework used to provide these components is Symfony2, and the Web App component provides a AngularJS frontend
application.

Each component lives in its own Symfony2 module. External dependencies are managed through Composer (for the PHP
backend) and Bower (for the JavaScript frontends).

Development, test and build tasks are managed through `make` (for the PHP backend) and Grunt (for the AngularJS
frontend).


## Working on the project

In order to start developing, testing and using this project, use the following recipe:

### Requirements

Your local development environment needs to have the following software installed and runnable:

* Git
* sqlite3
* PHP >= 5.3.3 with gd and sqlite support
* make
* composer
* Node.js >= 0.10.28 with NPM >= 1.3.11
* Bower

### Installing the requirements

This part of course depends on your actual system. However, here is how to set up your system if you are running Mac OS
X and use Homebrew. We are going to use the most recent stable version available for the requirements.

    cd

    brew install git php55

    curl -O http://nodejs.org/dist/v0.10.29/node-v0.10.29.tar.gz
    tar xvfz node-v0.10.29.tar.gz
    cd node-v0.10.29
    ./configure
    make
    sudo make install

    sudo npm install -g bower

    curl -sS https://getcomposer.org/installer | php
    sudo ln -s ~/composer.phar /usr/bin/composer

### Getting the project sources

    git clone https://github.com/manuelkiessling/soundvenirs-backend.git
    cd soundvenirs-backend

### Setting up the project and starting a dev server

This is achieved by simply running

    make

You will now have a development web server running at `localhost:8080`. Visit http://localhost:8080/app_dev.php/


## Test architecture, workflow, and tools

This project is developed in a fully test-driven manner, with tests at every layer:

    +-----------------------------------------+
    |                                         |
    |       Protractor End-to-End Tests       |
    |                                         |
    +-----------------------------------------+
                                               
    +------------------+                       
    |                  |                       
    | Symfony2         |                       
    | Functional Tests |                       
    |                  |                       
    +------------------+                       
                                               
    +------------------+   +------------------+
    |                  |   |                  |
    | Symfony2         |   | Angular          |
    | Unit tests       |   | Unit tests       |
    |                  |   |                  |
    +------------------+   +------------------+
                                               
         Backend                 Frontend      

Both the Symfony2 PHP backend and the AngularJS JavaScript Frontend have a suite of unit tests that cover the
functionality of low level application modules. The Symfony2 PHP backend also has a suite of functional tests that
are implemented using the Symfony2 WebTestCase infrastructure and cover the functionality of the backend request
controllers.

A Protractor-based end-to-end test setup is used to verify the functionality of the fully integrated system from a browser's
point of view.

At the unit test level, external dependencies like databases (backend) or API endpoints (frontend) are mocked. On the
functional and end-to-end level, nothing is simulated.

In order to execute the Symfony2 PHP backend unit and functional tests, run `make backend-test`. In order to execute
the AngularJS frontend unit tests, run `make webapp-test`. In order to execute the End-to-End test suite, run
`make e2e-test`.

In order to execute all three test suites, run `make test`.


## Setting up the production server environment

The following describes the steps you must take in order to create an environment which allows the production website
and webservice API to be served from this environment.

### Requirements

* Ubuntu 12.04 64bit
* git
* sqlite3
* php5-fpm
* php5-gd
* php5-sqlite
* composer
* npm >= 1.3.11
* bower
* nginx

I'm pretty sure it works with more recent versions of Ubuntu, but I haven't verified it.

### Installing the requirements

    cd

    sudo apt-get install git nginx sqlite3 php5-cli php5-fpm php5-gd php5-sqlite

    wget http://nodejs.org/dist/v0.10.29/node-v0.10.29.tar.gz
    tar xvfz node-v0.10.29.tar.gz
    cd node-v0.10.29
    ./configure
    make
    sudo make install

    sudo npm install -g bower

    curl -sS https://getcomposer.org/installer | php
    sudo mv ./composer.phar /usr/bin/composer

### Setting up the environment

Set `listen` to `127.0.0.1:9001` in `/etc/php5/fpm/pool.d/www.conf`.

Create `/etc/nginx/sites-available/soundvenirs.com` with the following content:

    server {
        server_name www.soundvenirs.com soundvenirs.com;
        listen 80;
        access_log /var/log/nginx/soundvenirs.com.access.log;
        error_log /var/log/nginx/soundvenirs.com.error.log;
        charset utf-8;
        client_max_body_size 20M;

        root /opt/soundvenirs-backend/web;
        index index.php;

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9001;
            fastcgi_index app.php;
            fastcgi_param PHP_VALUE upload_max_filesize=20M;
            include fastcgi_params;
        }

        location / {
            if (-f $request_filename) {
                expires max;
                break;
            }
            rewrite ^(.*) /app.php last;
        }
    }

Then run

    sudo mkdir -p /opt/soundvenirs-backend
    sudo mkdir -p /var/log/soundvenirs-backend
    sudo mkdir -p /var/lib/soundvenirs-backend/cache
    sudo mkdir -p /var/lib/soundvenirs-backend/db
    sudo mkdir -p /var/lib/soundvenirs-backend/soundfiles

    cd /opt
    sudo git clone https://github.com/manuelkiessling/soundvenirs-backend.git
    cd soundvenirs-backend
    sudo make production

    sudo chown -R www-data:www-data /var/log/soundvenirs-backend
    sudo chown -R www-data:www-data /opt/soundvenirs-backend/var
    sudo chown -R www-data:www-data /var/lib/soundvenirs-backend

    sudo ln -s /etc/nginx/sites-available/soundvenirs.com /etc/nginx/sites-enabled/
    sudo service php5-fpm restart
    sudo service nginx restart

If you point the A record for *www.soundvenirs.com* at the IP address of the production server, you are now able to
access the website at http://www.soundvenirs.com/.


## Continuous Delivery setup

The following describes the steps you must take in order to set up a Continuous Delivery workflow for the website and
webservice API.

As a result, every commit to the *master* branch of *git@github.com:manuelkiessling/soundvenirs-backend.git* that
results in a successful TravisCI run will be released to the production server environment.

### The workflow

Continuous Delivery works by combining TravisCI, GitHub release tags, and a SimpleCD cronjob.

                                  GITHUB                                                      
                                  +---------------+                                           
                                  |               |                                           
                   push to master |               | pulls commit                              
        Developer  +------------> | commit 123456 | +------------> TravisCI                   
                                  |               |                  |                        
                                  |               |                  | Runs build and succeeds
                                  |               |                  |                        
                                  | release xyz   | <----------------/                        
                                  |     tag xyz   |   creates release                         
                                  |               |   (which creates tag)                     
                                  +-------+-------+                                           
                                          |                                                   
                                          |                                                   
                                          |                                                   
                                  SERVER  v                                                   
                                  +---------------+                                           
                                  | SimpleCD cron |                                           
                                  |               |                                           
                                  | - checks for  |                                           
                                  |   new tag     |                                           
                                  |               |                                           
                                  | - finds new   |                                           
                                  |   tag xyz     |                                           
                                  |               |                                           
                                  | - deploys code|                                           
                                  |               |                                           
                                  +---------------+                                           

Whenever a new revision is committed to the master branch of this repository, TravisCI will execute the test suite of
the project for this revision. If no failures occur, TravisCI will create a new release for the given revision, named
*travisci-build-{BUILDNUMBER}*.

On the production server, a SimpleCD cronjob observes the repository - if a new release matching the
*travisci-build-{BUILDNUMBER}* pattern is detected, then the revision of this release will be checked out and its
content copied to the project folder at */opt/soundvenirs-backend*.

### Setting up Continuous Delivery on the production server

    sudo apt-get install postfix mailutils
    cd /opt
    sudo git clone https://github.com/manuelkiessling/simplecd.git
    sudo echo -e "MAILTO=\"\"\n* * * * * root /opt/simplecd/simplecd.sh tag travisci-build-* https://github.com/manuelkiessling/soundvenirs-backend.git https://github.com/manuelkiessling/soundvenirs-backend/commit/" > /etc/cron.d/deploy-soundvenirs-backend
