<?php
/*
## Installing

Create, install and enable systemd service(s).

Require the recipe into your `deploy.php`

```php
require '<PATH>/systemd-service.php';
```

Add hooks to your deployment:

```php
before('deploy:prepare', 'systemd-service:stop')
after('deploy:symlink', 'systemd-service:start')
after('systemd-service:start', 'systemd-service:status');'
```

This configuration stops the systemd service(s) at the start of the deployment process.

After the new code is in place (`current` symlink exists), the service(s) are
started and the service(s) status is requested.

## Configuration

 - `systemd_service_names` - array of one or more service names (default: `[]`)

 - `systemd_systemctl_use_sudo` - use `sudo` for systemctl commands (default: `true`)

 - `systemd_systemctl_command` - the used systemctl binary (default: `systemctl`)

 */

declare(strict_types=1);

namespace Deployer;

// Configuration variables

set('systemd_service_names', []);
set('systemd_systemctl_use_sudo', true);
set('systemd_systemctl_command', 'systemctl');

// Tasks

desc('Shows systemd service(s) status');
task('systemd-service:status', function () {
    foreach (get('systemd_service_names') as $systemdServiceName) {
        systemdServiceCommand('status', $systemdServiceName);
    }
});

desc('Stops systemd service(s)');
task('systemd-service:stop', function () {
    foreach (get('systemd_service_names') as $systemdServiceName) {
        systemdServiceCommand('stop', $systemdServiceName);
    }
});

desc('Starts systemd service(s)');
task('systemd-service:start', function () {
    foreach (get('systemd_service_names') as $systemdServiceName) {
        systemdServiceCommand('start', $systemdServiceName);
    }
});

function systemdServiceCommand(string $action, string $serviceName): string
{
    $command = get('systemd_systemctl_command') . ' ' . $action . ' ' . $serviceName;
    if (get('systemd_systemctl_use_sudo') === true) {
        $command = 'sudo ' . $command;
    }

    return run($command);
}
