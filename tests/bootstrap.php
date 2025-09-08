<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if (isset($_ENV['BOOTSTRAP_CREATE_TEST_DATABASE'])) {
     // executes the "php bin/console --env=test doctrine:database:create" command
     passthru(sprintf(
         'php "%s/../bin/console" --env=test doctrine:database:create',
         __DIR__
     ));
}

if (isset($_ENV['BOOTSTRAP_CREATE_TEST_SCHEMA'])) {
     // executes the "php bin/console --env=test doctrine:schema:create" command
     passthru(sprintf(
         'php "%s/../bin/console" --env=test doctrine:schema:create',
         __DIR__
     ));
}
