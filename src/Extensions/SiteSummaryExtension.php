<?php

class SiteSummaryExtension extends Extension
{
    /**
     * Update screen bound report columns to remove the text (csv) column
     * listing CVE numbers, and include the view assets to render appropriately
     *
     * @param array $columns Report display columns
     */
    public function updateColumns(&$columns)
    {
        Requirements::css('silverstripe-composer-security-checker/css/securityalerts.css');
        Requirements::javascript('silverstripe-composer-security-checker/javascript/summaryalerts.js');
        unset($columns['listCVEs']);
    }

    /**
     * Update the Package's screen bound summary info with little badges to indicate
     * security alerts are present for this package
     *
     * @param array $alerts a list of alerts to display
     */
    public function updateAlerts(&$alerts)
    {
        $securityWarnings = $this->owner->sourceRecords()->filter('SecurityAlerts.ID:GreaterThan', 0);

        if ($securityWarnings->exists()) {
            $alerts['SecurityAlerts'] = $securityWarnings->renderWith('SecurityAlertSummary');
        }
    }
}
