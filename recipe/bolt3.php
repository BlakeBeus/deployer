<?php
namespace Deployer;
require_once __DIR__ . '/common.php';

// Configuration
set('shared_files', []);

set('shared_dirs', [
    'public/files',
    'public/thumbs',
    'extensions',
    'public/extensions',
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
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

after('deploy', 'success');
