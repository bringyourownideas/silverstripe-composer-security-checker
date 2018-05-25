<?php

class CVEExtension extends DataExtension
{
    private static $has_one = [
        'PackageRecord' => 'Package'
    ];
}
