#!/bin/bash

# Author Lizwi Silimela | lizwi@webgap.co.za
# Used inside Bitbucket pipelines. Builds our server for testing stage.

# Update/Install Packages
# use -y flag to prevent "Do you want to continue [Y/n]?" prompt from breaking our build process.
# use -q flag to make apt-get show less infomration in the logs (less noise).

apt -qy update
apt -qy install curl git zip unzip libzip-dev 
docker-php-ext-install pdo_mysql ctype bcmath zip 

# Install exif extension
docker-php-ext-configure exif
docker-php-ext-install exif

# Install Composer
curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install NPM
apt -qy install npm --force
