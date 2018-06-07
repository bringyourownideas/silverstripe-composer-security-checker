<?php

namespace BringYourOwnIdeas\SecurityChecker\Tests\Extensions;

use BringYourOwnIdeas\Maintenance\Model\Package;
use SilverStripe\Dev\SapphireTest;

class PackageSecurityExtensionTest extends SapphireTest
{
    protected static $fixture_file = 'PackageSecurityExtensionTest.yml';

    protected function setUp()
    {
        if (!class_exists(Package::class)) {
            static::$fixture_file = null;
            $this->markTestSkipped('This test class requires the maintenance module to be installed');
        }
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
}
