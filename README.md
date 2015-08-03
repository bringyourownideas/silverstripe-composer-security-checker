# SilverStripe composer security checker

Adds a task which runs a check if any of the dependencies has known security vulnerabilities.

**Recommendation is to only install this as dev dependency as the version information can be used by a potential attacker**

## Requirements

* SilverStripe Framework 3.*

## Installation and usage

1. Run the following commands to install this package as a development dependency:
   ```
   composer require spekulatius/silverstripe-composer-security-checker --dev
   ```

2. Login as an administrator

3. Go onto your website mysite.com/dev/tasks and find the security checker task.

4. Run the task and wait for the results. This can take some time - depending on the number of dependencies.
