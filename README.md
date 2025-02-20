
## Installation

```shell
composer require --dev mjaschen/deployer-tasks 
```

Include the tasks in your `deploy.php`:

```php
<?php

namespace Deployer;

require 'recipe/common.php';
require 'systemd-service.php';
require 'supervisord-service.php';
```

## supervisord Services

Manages (already registered) [*supervisord*](http://supervisord.org/) services.

Example: stopping services before deployment, starting services when updated files are in place; show service status afterwards:

```php
set('supervisord_service_names', ['acme_worker']);

// (optional) when supervisor command is not in $PATH:
set('supervisorctl_command', '/opt/supervisor/supervisorctl');

before('deploy:prepare', 'supervisord-service:stop')
after('deploy:symlink', 'supervisord-service:start')
after('supervisord-service:start', function() {
    sleep(1);
});
after('supervisord-service:start', 'supervisord-service:status');
```

### Configuration

Alle possible configuration options with their default values:

```php
set('supervisord_service_names', []);
set('supervisorctl_use_sudo', true);
set('supervisorctl_command', 'supervisorctl');
```

## systemd Services

Manages (already registered) systemd services and timers.

Example: stopping services before deployment, starting services when updated files are in place; show service status afterwards:

```php
set('systemd_service_names', ['acme_worker.service']);
set('systemd_timer_names', ['acme_scheduler.timer']);

before('deploy:prepare', 'systemd-service:stop')
before('deploy:prepare', 'systemd-timer:stop')
after('deploy:symlink', 'systemd-service:start')
after('deploy:symlink', 'systemd-timer:start')
after('systemd-service:start', function() {
    sleep(1);
});
after('systemd-timer:start', function() {
    sleep(1);
});
after('systemd-service:start', 'systemd-service:status');
after('systemd-timer:start', 'systemd-timer:status');
```

### Configuration

All possible configuration options with their default values:

```php
set('systemd_service_names', []);
set('systemd_timer_names', []);
set('systemd_systemctl_use_sudo', true);
set('systemd_systemctl_command', 'systemctl');
```
