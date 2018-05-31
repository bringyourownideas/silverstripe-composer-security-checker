<?php

class SecurityAlertExtension extends DataExtension
{
    private static $has_one = [
        'PackageRecord' => 'Package'
    ];
}
