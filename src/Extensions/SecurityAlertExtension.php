<?php

namespace BringYourOwnIdeas\SecurityChecker\Extensions;

use BringYourOwnIdeas\Maintenance\Model\Package;
use SilverStripe\ORM\DataExtension;

class SecurityAlertExtension extends DataExtension
{
    private static $has_one = [
        'PackageRecord' => Package::class
    ];
}
