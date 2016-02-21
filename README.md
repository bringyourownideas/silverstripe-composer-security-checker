# SilverStripe composer security checker

[![Build Status](https://api.travis-ci.org/spekulatius/silverstripe-composer-security-checker.svg?branch=master)](https://travis-ci.org/spekulatius/silverstripe-composer-security-checker)
[![Latest Stable Version](https://poser.pugx.org/spekulatius/silverstripe-composer-security-checker/version.svg)](https://github.com/spekulatius/silverstripe-composer-security-checker/releases)
[![Latest Unstable Version](https://poser.pugx.org/spekulatius/silverstripe-composer-security-checker/v/unstable.svg)](https://packagist.org/packages/spekulatius/silverstripe-composer-security-checker)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/spekulatius/silverstripe-composer-security-checker.svg)](https://scrutinizer-ci.com/g/spekulatius/silverstripe-composer-security-checker?branch=master)
[![Total Downloads](https://poser.pugx.org/spekulatius/silverstripe-composer-security-checker/downloads.svg)](https://packagist.org/packages/spekulatius/silverstripe-composer-security-checker)
[![License](https://poser.pugx.org/spekulatius/silverstripe-composer-security-checker/license.svg)](https://github.com/spekulatius/silverstripe-composer-security-checker/blob/master/license.md)

Adds a task which runs a check if any of the dependencies has known security vulnerabilities. It uses the
[SensioLabs Security Check Web service][1] and the [Security Advisories Database][2].

*Because this module could expose information to an potential attacker the information doesn't get displayed!
You need to take care of processing this information somehow! E.g. using the [SilverStripe Maintenance module](https://github.com/FriendsOfSilverStripe/silverstripe-maintenance)*

## Requirements

* SilverStripe Framework ^3.0
* SilverStripe QueuedJobs *

## Installation

The following installation commands includes schedulding a queuedjob to populate the data. Run the following command to install this package as a development dependency:

```
composer require spekulatius/silverstripe-composer-security-checker dev-master --dev
php ./framework/cli-script.php dev/build
php ./framework/cli-script.php dev/tasks/ProcessJobQueueTask
```

*or* as general dependency:

```
composer require spekulatius/silverstripe-composer-security-checker dev-master
php ./framework/cli-script.php dev/build
php ./framework/cli-script.php dev/tasks/ProcessJobQueueTask
```

**Recommendation is to only install this as dev dependency!**

## Usage

The information gets automatically updated via a queuedjob on dev/build. You will need to run the queuedjobs task to get the information.

Use the information in your database (Table "ComposerSecurityVulnerability") as you like. Please be careful how you expose this information.

Future development / Ideas
--------------------------

* Output of the information (e.g. as JSON or XML?) to allow automatic processing... Via token authentication?


[1]: http://security.sensiolabs.org/
[2]: https://github.com/FriendsOfPHP/security-advisories
