#!/bin/bash

ddev delete -Oy
rm -rf .Build
rm -rf var
rm composer.lock

ddev start
ddev import-db --src=Build/Data/db.sql
ddev composer install
mkdir -p .Build/public/fileadmin
ddev launch typo3
