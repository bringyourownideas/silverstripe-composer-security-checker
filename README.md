# SilverStripe Security Checker

[![Build Status](https://api.travis-ci.org/bringyourownideas/silverstripe-composer-security-checker.svg?branch=master)](https://travis-ci.org/bringyourownideas/silverstripe-composer-security-checker)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bringyourownideas/silverstripe-composer-security-checker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bringyourownideas/silverstripe-composer-security-checker/?branch=master)
[![codecov](https://codecov.io/gh/bringyourownideas/silverstripe-composer-security-checker/branch/master/graph/badge.svg)](https://codecov.io/gh/bringyourownideas/silverstripe-composer-security-checker)

Adds a task which runs a check if any of the dependencies has known security vulnerabilities. It uses the
[SensioLabs Security Check Web service](http://security.sensiolabs.org/) and the [Security Advisories Database](https://github.com/FriendsOfPHP/security-advisories).

BSD 3-clause [License](https://github.com/bringyourownideas/silverstripe-composer-security-checker/blob/master/license.md)

## Requirements

* SilverStripe Framework ^4
* SilverStripe QueuedJobs ^4

### Suggested Module

This module will automatically amend the SiteSummary report provided by the [SilverStripe Maintenance module](https://github.com/bringyourownideas/silverstripe-maintenance), adding alerts if security updates are present for installed modules.

## Installation

The following installation commands includes schedulding a queuedjob to populate the data. Run the following command to install this package as a development dependency:

```
composer require bringyourownideas/silverstripe-composer-security-checker 2.x-dev

vendor/bin/sake dev/build
vendor/bin/sake dev/tasks/ProcessJobQueueTask
```

## Usage

The information gets updated via a BuildTask, which in turn can be run via a queuedjob.
You will need to set up a scheduled process (e.g. `cron`) to run either the buildtask directly, or the task to process the queuedjobs queue in order to refresh the information.

Use the information is stored in the `SecurityAlert` object, and can be consumed as needed. Please be careful how you expose this information. If the SilverStripe Maintenance module is present, a relationship will be connected between `Package` and `SecurityAlert`.

## Documentation

Please see the user guide section of the [SilverStripe Maintenance module](https://github.com/bringyourownideas/silverstripe-maintenance/tree/1/docs/en/userguide).
