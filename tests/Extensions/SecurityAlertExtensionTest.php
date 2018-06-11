<?php

namespace BringYourOwnIdeas\SecurityChecker\Tests\Extensions;

use BringYourOwnIdeas\Maintenance\Model\Package;
use BringYourOwnIdeas\SecurityChecker\Models\SecurityAlert;
use SilverStripe\Dev\SapphireTest;
use Symbiote\QueuedJobs\Services\QueuedJobService;

class SecurityAlertExtensionTest extends SapphireTest
{
    protected static $fixture_file = 'PackageSecurityExtensionTest.yml';

    protected function setUp()
    {
        if (!class_exists(Package::class)) {
            static::$fixture_file = null;
            $this->markTestSkipped(
                'Module bringyourownideas/silverstripe-maintenance is required for this test, but is not present.'
            );
        }

        QueuedJobService::config()->set('use_shutdown_function', false);

        parent::setUp();
    }

    public function testExtensionAppliesWhenMaintenanceModuleIsPresent()
    {
        /** @var SecurityAlert $alert */
        $alert = $this->objFromFixture(SecurityAlert::class, 'two');

        $this->assertTrue($alert->PackageRecord()->exists());
    }
}
