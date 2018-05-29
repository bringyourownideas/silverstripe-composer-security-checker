<?php

class PackageSecurityExtension extends DataExtension
{
    private static $has_many = [
        'SecurityAlerts' => 'CVE'
    ];

    private static $summary_fields = [
        'listCVEs' => 'Security alerts',
    ];

    /**
     * Simply returns a comma separated list of active CVE numbers for this record.
     * Used in CSV exports as a type of brief indication (as opposed to full info)
     */
    public function listCVEs()
    {
        $alerts = $this->owner->SecurityAlerts()->Column('CVE');
        return $alerts ? implode(', ', $alerts) : null;
    }

    /**
     * Renders the SecurityAlerts relationship
     * intended for use in the on screen version of the SiteSummary Report.
     */
    public function listAlerts()
    {
        $templates = ['PackageSecurityAlerts'];
        $this->owner->extend('updateListAlerts', $templates);
        return $this->owner->renderWith($templates);
    }

    /**
     * updates the badges that render as part of the screen targeted
     * summary for this Package
     *
     * @param array $badges in the format of [title => type]
     */
    public function updateBadges(&$badges)
    {
        if ($this->owner->SecurityAlerts()->exists()) {
            $badges['RISK: Security'] = 'warning';
        }
    }

    /**
     * Appends our own summary info to that of the default output
     * of the Package getSummary method.
     *
     * @param HTMLText $summary
     */
    public function updateSummary(&$summary)
    {
        $summary->setValue($summary . $this->listAlerts());
    }
}
