# SilverStripe composer security checker

Adds a task which runs a check if any of the dependencies has known security vulnerabilities. It uses the
[SensioLabs Security Check Web service][1] and the [Security Advisories Database][2].

*Because this module could expose information to an potential attacker the information doesn't get displayed!
You need to take care of processing this information somehow! E.g. using the [SilverStripe Maintenance module](https://github.com/FriendsOfSilverStripe/silverstripe-maintenance)*

**Recommendation is to only install this as dev dependency!**

## Requirements

* SilverStripe Framework 3.*

## Installation

Run the following command to install this package as a development dependency:
   ```
   composer require spekulatius/silverstripe-composer-security-checker --dev
   ```

*or* as general dependency:

   ```
   composer require spekulatius/silverstripe-composer-security-checker
   ```

## Usage

After the installation execute the following steps:

1. Go onto your website mysite.com/dev/tasks and find the security checker task.

2. Run the task and wait for the task to finish (displays message). This can take some time - depending on the number of dependencies.

3. Use the information in your database (Table "ComposerSecurityVulnerability").

Future development / Ideas
--------------------------

* Output of the information (e.g. as JSON or XML?) to allow automatic processing... Via token authentication?


[1]: http://security.sensiolabs.org/
[2]: https://github.com/FriendsOfPHP/security-advisories
