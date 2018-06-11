<?php

namespace BringYourOwnIdeas\SecurityChecker\Tests\Stubs;

use SilverStripe\Core\Extension;
use SilverStripe\Dev\TestOnly;

class SiteSummaryAlertStub extends Extension implements TestOnly
{
    public function updateAlerts(&$alerts)
    {
        $alerts[] = '<p><strong>Alert! Alert!</strong> <br />Sound the alarm!</p>';
    }
}
