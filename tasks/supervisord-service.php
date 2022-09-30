<?php
/*
## Installing

Create, install and enable supervisord service(s).

Require the recipe into your `deploy.php`

```php
require '<PATH>/supervisord-service.php';
```

Add hooks to your deployment:

```php
before('deploy:prepare', 'supervisord-service:stop')
after('deploy:symlink', 'supervisord-service:start')
after('supervisord-service:start', 'supervisord-service:status');'
```

This configuration stops the supervisord service(s) at the start of the deployment process.

After the new code is in place (`current` symlink exists), the service(s) are
started and the service(s) status is requested.

## Configuration

 - `supervisord_service_names` - array of one or more service names (default: `[]`)

 - `supervisorctl_use_sudo` - use `sudo` for supervisord commands (default: `true`)

 - `supervisorctl_command` - the used supervisorctl binary (default: `supervisorctl`)

 */

declare(strict_types=1);

namespace Deployer;

set('supervisord_service_names', []);
set('supervisorctl_use_sudo', true);
set('supervisorctl_command', 'supervisorctl');

desc('Shows supervisord service(s) status');
task('supervisord-service:status', function () {
    foreach (get('supervisord_service_names') as $supervisordServiceName) {
        supervisorctlCommand('status', $supervisordServiceName);
    }
});

desc('Stops supervisord service(s)');
task('supervisord-service:stop', function () {
    foreach (get('supervisord_service_names') as $supervisordServiceName) {
        supervisorctlCommand('stop', $supervisordServiceName);
    }
});

desc('Starts supervisord service(s)');
task('supervisord-service:start', function () {
    foreach (get('supervisord_service_names') as $supervisordServiceName) {
        supervisorctlCommand('start', $supervisordServiceName);
    }
});

function supervisorctlCommand(string $action, string $serviceName): string
{
    $command = get('supervisorctl_command') . ' ' . $action . ' ' . $serviceName;
    if (get('supervisorctl_use_sudo') === true) {
        $command = 'sudo ' . $command;
    }

    return run($command);
}
