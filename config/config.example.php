<?php
return [
    'debug'  => true,
    'deploy' => [
        'host'          => 'localhost',
        'user'          => 'api',
        'password'      => 'api',//OR private_key
        'private_key'   => '',
        'ref'           => 'origin/master',
        'repo'          => 'git@github.com:exileed/deploy.git',
        'path'          => '/home/api/backend',
        'post_deploy'   => 'composer install --no-dev && php bin/console migrate',
        'post_rollback' => 'php bin/console rollback',
    ],
];