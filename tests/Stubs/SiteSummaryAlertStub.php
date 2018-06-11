<?php

namespace BringYourOwnIdeas\SecurityChecker\Tests\Stubs;

use Extension;
use TestOnly;

class SiteSummaryAlertStub extends Extension implements TestOnly
{
    public function updateAlerts(&$alerts)
    {
        $alerts[] = '<p><strong>Alert! Alert!</strong> <br />Sound the alarm!</p>';
    }
}
