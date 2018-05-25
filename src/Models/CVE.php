<?php
/**
 * Describes a known security issue of an installed Composer package
 */
class CVE extends DataObject
{
    private static $db = array(
        'PackageName' => 'Varchar(255)',
        'Version' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
        'ExternalLink' => 'Varchar(255)',
        'CVE' => 'Varchar(255)',
    );

    private static $summary_fields = array(
        'PackageName',
        'Version',
        'Title',
    );
}
