<?php

namespace BringYourOwnIdeas\SecurityChecker\Tests\Extensions;

use BringYourOwnIdeas\Maintenance\Model\Package;
use SilverStripe\Dev\SapphireTest;
use Symbiote\QueuedJobs\Services\QueuedJobService;

class PackageSecurityExtensionTest extends SapphireTest
{
    protected static $fixture_file = 'PackageSecurityExtensionTest.yml';

    protected function setUp()
    {
        if (!class_exists(Package::class)) {
            static::$fixture_file = null;
            $this->markTestSkipped('This test class requires the maintenance module to be installed');
        }

        QueuedJobService::config()->set('use_shutdown_function', false);

        parent::setUp();
    }

    public function testAlertsAreIncludedInDataSchema()
    {
        /** @var Package $package */
        $package = $this->objFromFixture(Package::class, 'package_a');

        $dataSchema = $package->getDataSchema();
        $this->assertArrayHasKey('securityAlerts', $dataSchema);
        $this->assertNotEmpty($dataSchema['securityAlerts']);

        $firstAlert = $dataSchema['securityAlerts'][0];
        $this->assertEquals('SS-123-456', $firstAlert['Identifier']);
        $this->assertEquals('silverstripe.org', $firstAlert['ExternalLink']);
    }

    public function testListSecurityAlertIdentifiers()
    {
        /** @var Package $package */
        $package = $this->objFromFixture(Package::class, 'otheralerts');

        $this->assertEquals('ABC-001, SPY-007', $package->listSecurityAlertIdentifiers());
    }

    public function testGetBadgesHook()
    {
        /** @var Package $package */
        $package = $this->objFromFixture(Package::class, 'otheralerts');

        $badges = $package->getBadges();
        $this->assertCount(1, $badges);
        $this->assertEquals('warning security-alerts__toggler', $badges->first()->Type);
    }
}
