# DiGi Portfolio (PHP & Symfony)

A project brought back to live with the plan to make it live, eventually.

Main purpose is to collect evidence of learning, allow evaluation and present the results in concise and effective manner.

## Note

This code has its cousin in Python and Django. It has been created for my learning purposes and it looks like Symfony won. 😎

## Installation notes (Symfony v5.54)

1. Download GIT repository
2. Create DB digiportfolio and restore DB (Plain)
3. Run following commands:
composer --version
composer install
symfony --version
scoop install main/nodejs-lts
node --version
npm --version
npm install --legacy-peer-deps
npm watch (constant build) or npm run dev (one build)
pg_ctl start
symfony server:start --port 8080

## Dev notes

### Run Unit tests

To create and start DB:
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
Note: It is also part of tests/bootstrap.php

To make fixtures for the test DB (optional):
php bin/console make:fixtures
php bin/console --env=test doctrine:fixtures:load
php bin/console --env=test doctrine:schema:update --force --complete

To start test:
php bin/phpunit

### Rector code

composer required rector/rector --dev
./vendor/bin/rector (Initiates rector.php with rules.)
./vendor/bin/rector --version
./vendor/bin/rector
