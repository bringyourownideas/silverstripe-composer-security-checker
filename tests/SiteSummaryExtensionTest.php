<?php

use BringYourOwnIdeas\SecurityChecker\Tests\Stubs\SiteSummaryAlertStub;

class SiteSummaryExtensionTest extends SapphireTest
{
    protected static $fixture_file = 'PackagesWithAlerts.yml';

    protected $requiredExtensions = [
        SiteSummary::class => [SiteSummaryAlertStub::class]
    ];

    public function setUpOnce()
    {
        if (!class_exists(Package::class)) {
            $this->requiredExtensions = [];
        }
        parent::setUpOnce();
    }

    public function setUp()
    {
        if (!class_exists(Package::class)) {
            $this->markTestSkipped(
                'Module bringyourownideas/silverstripe-maintenance is required for this test, but is not present.'
            );
        }
        // The themes should not affect test results.
        // Ensure we use the default templates supplied with this module.
        Config::inst()->update(SSViewer::class, 'theme_enabled', false);
        parent::setUp();
    }

    public function testDisplayColumnsDoesNotIncludePrintingColumns()
    {
        $report = Injector::inst()->create(SiteSummary::class);
        // screen output should not include this summary field added by the extension
        $this->assertArrayNotHasKey('listSecurityAlertIdentifiers', $report->columns());
        // default summary fields are still used for print output (e.g. CSV export)
        $this->assertArrayHasKey('listSecurityAlertIdentifiers', Package::create()->summaryFields());
    }

    public function testUpdateAlerts()
    {
        $report = Injector::inst()->create(SiteSummary::class);
        $fields = $report->getCMSFields();
        $alertSummary = $fields->fieldByName('AlertSummary');
        $this->assertInstanceOf(LiteralField::class, $alertSummary);
        $content = $alertSummary->getContent();
        $this->assertContains(
            'Sound the alarm!',
            $content,
            'ensure our extension doesn\'t override all others'
        );
        $this->assertContains(
            'site-summary__security-alerts',
            $content,
            'ensure content from our extension is present'
        );
        $this->assertContains(
            '<strong>2</strong>',
            $content,
            'ensure we are counting modules with alerts, not the total number of alerts'
        );
    }
}
