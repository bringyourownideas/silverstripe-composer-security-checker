<?php

class SecurityAlertExtensionTest extends SapphireTest
{
    protected static $fixture_file = 'PackagesWithAlerts.yml';

    public function setUp()
    {
        if (!class_exists(Package::class)) {
            $this->markTestSkipped(
                'Module bringyourownideas/silverstripe-maintenance is required for this test, but is not present.'
            );
        }
        parent::setUp();
    }

    public function testExtensionAppliesWhenMaintenanceModuleIsPresent()
    {
        $alert = $this->objFromFixture(SecurityAlert::class, 'two');
        $this->assertTrue($alert->PackageRecord()->exists());
    }
}
