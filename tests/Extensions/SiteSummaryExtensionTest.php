<?php

namespace BringYourOwnIdeas\SecurityChecker\Tests\Extensions;

use BringYourOwnIdeas\Maintenance\Model\Package;
use BringYourOwnIdeas\Maintenance\Reports\SiteSummary;
use BringYourOwnIdeas\SecurityChecker\Tests\Stubs\SiteSummaryAlertStub;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\LiteralField;
use SilverStripe\View\SSViewer;
use Symbiote\QueuedJobs\Services\QueuedJobService;

class SiteSummaryExtensionTest extends SapphireTest
{
    protected static $fixture_file = 'PackageSecurityExtensionTest.yml';

    protected static $required_extensions = [
        SiteSummary::class => [SiteSummaryAlertStub::class]
    ];

    protected function setUp()
    {
        if (!class_exists(Package::class)) {
            static::$fixture_file = null;
            static::$required_extensions = [];

            $this->markTestSkipped(
                'Module bringyourownideas/silverstripe-maintenance is required for this test, but is not present.'
            );
        }

        QueuedJobService::config()->set('use_shutdown_function', false);

        // The themes should not affect test results.
        // Ensure we use the default templates supplied with this module.
        Config::modify()->set(SSViewer::class, 'theme_enabled', false);

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
        /** @var SiteSummary $report */
        $report = Injector::inst()->create(SiteSummary::class);
        $fields = $report->getCMSFields();

        /** @var LiteralField $alertSummary */
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
