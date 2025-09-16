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
Run Unit tests:
php vendor/bin/phpunit