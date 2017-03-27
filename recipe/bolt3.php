<?php
namespace Deployer;
require_once __DIR__ . '/common.php';

// Configuration

set('copy_dirs', [
    'public/files',
    'public/thumbs',
    'extensions',
    'public/extensions',
    'extensions',
    'vendor'

]);


set('writable_dirs', [
    'public/files',
    'public/thumbs',
    'extensions',
    'public/extensions',
    'app/config',
    'app/cache',
    'public/theme',
    'vendor'
]);


desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    // The user must have rights for restart service without sudo password if you get the below message
    //[RuntimeException]
    //sudo: no tty present and no askpass program specified
    //sudo visudo
    //username ALL=NOPASSWD: /usr/sbin/service php7.1-fpm reload
    run('sudo service php7.1-fpm reload');
});


// Bolt tasks
desc('Clear Bolt CMS cache and check DB after deploy');
task('bolt3:reload', function(){
    run('{{bin/php}} {{release_path}}/app/nut cache:clear');
    run('{{bin/php}} {{release_path}}/app/nut database:update');
});

after('deploy:unlock', 'bolt3:reload');


desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:writable',
    'deploy:copy_dirs',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

after('deploy', 'success');
