<?php

namespace BringYourOwnIdeas\SecurityChecker\Extensions;

use SilverStripe\View\Requirements;
use SilverStripe\Core\Extension;

class SiteSummaryExtension extends Extension
{
    /**
     * Update screen bound report columns to remove the text (csv) column
     * listing SecurityAlert Identifier numbers, and include the view assets to render appropriately
     *
     * @param array $columns Report display columns
     */
    public function updateColumns(&$columns)
    {
        Requirements::css(
            'bringyourownideas/silverstripe-composer-security-checker: client/dist/css/securityalerts.css'
        );
        Requirements::javascript(
            'bringyourownideas/silverstripe-composer-security-checker: client/dist/javascript/summaryalerts.js'
        );
        unset($columns['listSecurityAlertIdentifiers']);
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
