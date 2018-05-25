<?php

class PackageSecurityExtension extends DataExtension
{
    private static $has_many = [
        'SecurityAlerts' => 'CVE'
    ];

    private static $summary_fields = [
        'listCVEs' => 'Security alerts',
    ];

    public function listCVEs()
    {
        $alerts = $this->owner->SecurityAlerts()->Column('CVE');
        return $alerts ? implode(', ', $alerts) : null;
    }

    public function listAlerts()
    {
        $templates = ['PackageSecurityAlerts'];
        $this->owner->extend('updateListAlerts', $templates);
        return $this->owner->renderWith($templates);
    }
}
