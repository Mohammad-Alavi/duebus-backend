<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'duebus');

// Project repository
set('repository', 'https://github.com/Mohammad-Alavi/duebus-backend.git');

// Configuration
set('ssh_type', 'native');
set('ssh_multiplexing', false);
set('http_user', 'admin');
set('http_group', 'admin');
set('writable_mode', 'chown');
set('writable_chmod_recursive', true);
set('writable_use_sudo', true);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

set('branch', 'master');

// Servers
host('i-visitor.ir')
    ->user('admin')
    ->port(22)
    //->configFile('~/.ssh/config')
    ->identityFile('~/.ssh/1990')
    ->forwardAgent(true)
    ->set('deploy_path', '/home/admin/domains/i-visitor.ir');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

desc('Install Node Packages');
task('october:up', function () {
    run('cd {{release_path}} && php artisan october:up');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');

// Migrate using OctoberCMS up command after Laravel migrate
after('artisan:migrate', 'october:up');

