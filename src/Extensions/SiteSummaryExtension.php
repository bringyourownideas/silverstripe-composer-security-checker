<?php

class SiteSummaryExtension extends Extension
{
    public function updateColumns(&$columns)
    {
        unset($columns['listCVEs']);
    }

    public function updateCMSFields(&$fields)
    {
        $summaryInfo = $this->owner->sourceRecords()->renderWith('PackageSecurityAlerts');
        $fields->unshift(LiteralField::create('AlertSummary', $summaryInfo));
    }
}
