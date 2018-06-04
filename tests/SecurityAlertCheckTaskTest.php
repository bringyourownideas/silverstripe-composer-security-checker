<?php

use SensioLabs\Security\SecurityChecker;

class SecurityAlertCheckTaskTest extends SapphireTest
{
    protected $usesDatabase = true;

    /**
     * @var SecurityAlertCheckTask
     */
    private $checkTask;

    /**
     * provide a mock to remove dependency on external service
     */
    protected function getSecurityCheckerMock($empty = false)
    {
        // Mock info comes from SensioLabs API docs example output,
        // and a real (test) silverstripe/installer 3.2.0 installation
        // (using the aforementioned API)
        $mockOutput = <<<CVENOTICE
{
    "symfony\/symfony": {
        "version": "2.1.x-dev",
        "advisories": {
            "symfony\/symfony\/CVE-2013-1397.yaml": {
                "title": "Ability to enable\/disable object support in YAML parsing and dumping",
                "link": "http:\/\/symfony.com\/blog\/security-release-symfony-2-0-22-and-2-1-7-released",
                "cve": "CVE-2013-1397"
            }
        }
    },
    "silverstripe\/framework": {
        "version": "3.2.0",
        "advisories": {
            "silverstripe\/framework\/SS-2016-002-1.yaml": {
                "title": "SS-2016-002: CSRF vulnerability in GridFieldAddExistingAutocompleter",
                "link": "https:\/\/www.silverstripe.org\/download\/security-releases\/ss-2016-002\/",
                "cve": ""
            },
            "silverstripe\/framework\/SS-2016-003-1.yaml": {
                "title": "SS-2016-003: Hostname, IP and Protocol Spoofing through HTTP Headers",
                "link": "https:\/\/www.silverstripe.org\/download\/security-releases\/ss-2016-003\/",
                "cve": ""
            },
            "silverstripe\/framework\/SS-2015-028-1.yaml": {
                "title": "SS-2015-028: Missing security check on dev\/build\/defaults",
                "link": "https:\/\/www.silverstripe.org\/download\/security-releases\/ss-2015-028\/",
                "cve": ""
            },
            "silverstripe\/framework\/SS-2015-027-1.yaml": {
                "title": "SS-2015-027: HtmlEditor embed url sanitisation",
                "link": "https:\/\/www.silverstripe.org\/download\/security-releases\/ss-2015-027\/",
                "cve": ""
            },
            "silverstripe\/framework\/SS-2015-026-1.yaml": {
                "title": "SS-2015-026: Form field validation message XSS vulnerability",
                "link": "https:\/\/www.silverstripe.org\/download\/security-releases\/ss-2015-026\/",
                "cve": ""
            }
        }
    }
}
CVENOTICE;

        $securityCheckerMock = $this->getMockBuilder(SecurityChecker::class)->setMethods(['check'])->getMock();
        $securityCheckerMock->expects($this->any())->method('check')->will($this->returnValue(
            $empty ? [] : json_decode($mockOutput, true)
        ));

        return $securityCheckerMock;
    }

    public function setUp()
    {
        parent::setUp();
        $securityCheckerMock = $this->getSecurityCheckerMock();
        $checkTask = new SecurityAlertCheckTask;
        $checkTask->setSecurityChecker($securityCheckerMock);
        $this->checkTask = $checkTask;
    }

    public function testUpdatesAreSaved()
    {
        $checkTask = $this->checkTask;

        $preCheck = SecurityAlert::get();
        $this->assertCount(0, $preCheck, 'database is empty to begin with');

        $checkTask->run(null);

        $postCheck = SecurityAlert::get();
        $this->assertCount(6, $postCheck, 'SecurityAlert has been stored');
    }

    public function testNoDuplicates()
    {
        $checkTask = $this->checkTask;

        $checkTask->run(null);

        $postCheck = SecurityAlert::get();
        $this->assertCount(6, $postCheck, 'SecurityAlert has been stored');
        
        $checkTask->run(null);

        $postCheck = SecurityAlert::get();
        $this->assertCount(6, $postCheck, 'The SecurityAlert isn\'t stored twice.');
    }

    public function testSecurityAlertRemovals()
    {
        $checkTask = $this->checkTask;

        $checkTask->run(null);

        $preCheck = SecurityAlert::get();
        $this->assertCount(6, $preCheck, 'database has stored SecurityAlerts');

        $securityCheckerMock = $this->getSecurityCheckerMock(true);
        $checkTask->setSecurityChecker($securityCheckerMock);

        $checkTask->run(null);

        $postCheck = SecurityAlert::get();
        $this->assertCount(0, $postCheck, 'database is empty to finish with');
    }

    public function testIdentifierSetsFromTitleIfCVEIsNotSet()
    {
        $checkTask = $this->checkTask;
        $checkTask->run(null);
        $frameworkAlert = SecurityAlert::get()
            ->filter('PackageName', 'silverstripe/framework')
            ->first();
        $this->assertNotEmpty($frameworkAlert->Identifier);
        $this->assertRegExp('/^SS-201[56]-\d{3}$/', $frameworkAlert->Identifier);
    }
}
