<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/npm.php';

// Config
set('application', 'wichtel.me');
set('git_ssh_command', 'ssh');
set('http_user', 'rondra');
set('repository', 'git@github.com:obstschale/wichteln.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

set('git_tty', true);

// Hosts

host('wichtel_prod')
    ->set('labels', ['stage' => 'prod'])
    ->set('remote_user', 'rondra')
    ->set('deploy_path', '~/html/{{application}}');

// Tasks
desc('Build npm assets');
task('npm:build', function () {
    run("cd {{release_path}} && {{bin/npm}} run build");
});

desc('Clear OPcache via HTTP');
task('opcache:reset', function () {
    $file = '{{release_path}}/public/opcache-reset.php';
    run("echo '<?php opcache_reset(); echo \"ok\";' > {$file}");
    run("curl -s https://{{application}}/opcache-reset.php");
    run("rm -f {$file}");
});

// Hooks
after('deploy:failed', 'deploy:unlock');
after('deploy:vendors', 'npm:install');
after('npm:install', 'npm:build');
after('deploy:symlink', 'opcache:reset');
