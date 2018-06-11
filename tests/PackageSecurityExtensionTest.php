<?php

class PackageSecurityExtensionTest extends SapphireTest
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

    public function testListSecurityAlertIdentifiers()
    {
        $package = $this->objFromFixture(Package::class, 'otheralerts');
        $this->assertEquals('ABC-001, SPY-007', $package->listSecurityAlertIdentifiers());
    }

    public function testGetBadgesHook()
    {
        $package = $this->objFromFixture(Package::class, 'otheralerts');
        $badges = $package->getBadges();
        $this->assertCount(1, $badges);
        $this->assertEquals('warning security-alerts__toggler', $badges->first()->Type);
    }
}
