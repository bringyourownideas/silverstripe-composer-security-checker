<?php

namespace BringYourOwnIdeas\SecurityChecker\Models;

use SilverStripe\ORM\DataObject;

/**
 * Describes a known security issue of an installed Composer package
 */
class SecurityAlert extends DataObject
{
    private static $table_name = 'SecurityAlert';

    private static $db = array(
        'PackageName' => 'Varchar(255)',
        'Version' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
        'ExternalLink' => 'Varchar(255)',
        'Identifier' => 'Varchar(255)',
    );

    private static $summary_fields = array(
        'PackageName',
        'Version',
        'Title',
    );
}
