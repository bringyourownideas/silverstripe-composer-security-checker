<?php

namespace BringYourOwnIdeas\SecurityChecker\Extensions;

use BringYourOwnIdeas\SecurityChecker\Models\SecurityAlert;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\View\ArrayData;

class PackageSecurityExtension extends DataExtension
{
    private static $has_many = [
        'SecurityAlerts' => SecurityAlert::class
    ];

    private static $summary_fields = [
        'listSecurityAlertIdentifiers' => 'Security alerts',
    ];

    /**
     * Simply returns a comma separated list of active SecurityAlert Identifiers for this record.
     * Used in CSV exports as a type of brief indication (as opposed to full info)
     */
    public function listSecurityAlertIdentifiers()
    {
        $alerts = $this->owner->SecurityAlerts()->Column('Identifier');
        return $alerts ? implode(', ', $alerts) : null;
    }

    /**
     * updates the badges that render as part of the screen targeted
     * summary for this Package
     *
     * @param ArrayList $badges
     */
    public function updateBadges($badges)
    {
        if ($this->owner->SecurityAlerts()->exists()) {
            $badges->push(ArrayData::create([
                'Title' => _t(__CLASS__ . '.BADGE_SECURITY', 'RISK: Security'),
                'Type' => 'warning security-alerts__toggler',
            ]));
        }
    }

    /**
     * Adds security alert notifications into the schema
     *
     * @param array &$schema
     * @return string
     */
    public function updateDataSchema(&$schema)
    {
        // The keys from the SecurityAlert model that we need in the React component
        $keysToPass = ['Identifier', 'ExternalLink'];

        $alerts = [];
        foreach ($this->owner->SecurityAlerts()->toNestedArray() as $alert) {
            $alerts[] = array_intersect_key($alert, array_flip($keysToPass));
        }

        $schema['securityAlerts'] = $alerts;
    }
}
