<?php

namespace BringYourOwnIdeas\SecurityChecker\Tests;

use BringYourOwnIdeas\SecurityChecker\Models\SecurityAlert;
use BringYourOwnIdeas\SecurityChecker\Tasks\SecurityAlertCheckTask;
use Signify\SecurityChecker\SecurityChecker;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\SapphireTest;
use Symbiote\QueuedJobs\Services\QueuedJobService;

class SecurityAlertCheckTaskTest extends SapphireTest
{
    protected $usesDatabase = true;

    /**
     * @var SecurityAlertCheckTask
     */
    private $checkTask;

    protected function setUp()
    {
        parent::setUp();

        QueuedJobService::config()->set('use_shutdown_function', false);

        $securityCheckerMock = $this->getSecurityCheckerMock();
        $checkTask = new SecurityAlertCheckTask;
        $checkTask->setSecurityChecker($securityCheckerMock);
        $this->checkTask = $checkTask;
    }

    /**
     * Run task buffering the output as so that it does not interfere with the test harness output.
     *
     * @param null|HTTPRequest $request
     *
     * @return string buffered output
     */
    private function runTask($request = null)
    {
        ob_start();
        $this->checkTask->run($request);
        return ob_get_clean();
    }

    /**
     * provide a mock to remove dependency on external service
     */
    protected function getSecurityCheckerMock($empty = false)
    {
        // Mock info comes from a real (test) silverstripe/framework 4.0.0 installation
        // using Signify's Composer Security Checker
        $mockOutput = [
            'league/flysystem' => [
                'version' => '1.0.70',
                'advisories' => [
                     [
                        'title' => 'TOCTOU Race Condition enabling remote code execution',
                        'link' => 'https://github.com/thephpleague/flysystem/security/advisories/GHSA-9f46-5r25-5wfm',
                        'cve' => 'CVE-2021-32708',
                     ],
                ],
            ],
            'silverstripe/assets' => [
                'version' => '1.1.0',
                'advisories' => [
                     [
                        'title' => 'CVE-2019-12245: Incorrect access control vulnerability in files uploaded to protected folders',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2019-12245/',
                        'cve' => 'CVE-2019-12245',
                     ],
                     [
                        'title' => 'CVE-2020-9280: Folders migrated from 3.x may be unsafe to upload to',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2020-9280/',
                        'cve' => 'CVE-2020-9280',
                     ],
                ],
            ],
            'silverstripe/framework' => [
                'version' => '4.0.0',
                'advisories' => [
                     [
                        'title' => 'CVE-2019-12203: Session fixation in \'change password\' form',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2019-12203/',
                        'cve' => 'CVE-2019-12203',
                     ],
                     [
                        'title' => 'CVE-2019-12246: Denial of Service on flush and development URL tools',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2019-12246',
                        'cve' => 'CVE-2019-12246',
                     ],
                     [
                        'title' => 'CVE-2019-14272: XSS in file titles managed through the CMS',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2019-14272/',
                        'cve' => 'CVE-2019-14272',
                     ],
                     [
                        'title' => 'CVE-2019-14273: Broken Access control on files',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2019-14273/',
                        'cve' => 'CVE-2019-14273',
                     ],
                     [
                        'title' => 'CVE-2019-16409: Secureassets and versionedfiles modules can expose versions of protected files',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2019-16409/',
                        'cve' => 'CVE-2019-16409',
                     ],
                     [
                        'title' => 'CVE-2019-19325: XSS through non-scalar FormField attributes',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2019-19325/',
                        'cve' => 'CVE-2019-19325',
                     ],
                     [
                        'title' => 'CVE-2019-19326: Web Cache Poisoning through HTTPRequestBuilder',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2019-19326/',
                        'cve' => 'CVE-2019-19326',
                     ],
                     [
                        'title' => 'CVE-2019-5715: Reflected SQL Injection through Form and DataObject',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-021',
                        'cve' => 'CVE-2019-5715',
                     ],
                     [
                        'title' => 'CVE-2020-26138 FormField: with square brackets in field name skips validation',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2020-26138',
                        'cve' => 'CVE-2020-26138',
                     ],
                     [
                        'title' => 'CVE-2020-6164: Information disclosure on /interactive URL path',
                        'link' => 'https://www.silverstripe.org/download/security-releases/cve-2020-6164/',
                        'cve' => 'CVE-2020-6164',
                     ],
                     [
                        'title' => 'SS-2017-007: CSV Excel Macro Injection',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2017-007/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2017-008: SQL injection in full text search of SilverStripe 4',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2017-008/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2017-009: Users inadvertently passing sensitive data to LoginAttempt',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2017-009/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2017-010: install.php discloses sensitive data by pre-populating DB credential forms',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2017-010/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2018-001: Privilege Escalation Risk in Member Edit form',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-001/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2018-005: isDev and isTest unguarded',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-005/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2018-008: BackURL validation bypass with malformed URLs',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-008/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2018-010: Member disclosure in login form',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-010/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2018-012: Uploaded PHP script execution in assets',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-012/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2018-018: Database credentials disclosure during connection failure',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-018/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2018-019: Possible denial of service attack vector when flushing',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-019/',
                        'cve' => null,
                     ],
                     [
                        'title' => 'SS-2018-020: Potential SQL vulnerability in PostgreSQL database connector',
                        'link' => 'https://www.silverstripe.org/download/security-releases/ss-2018-020/',
                        'cve' => null,
                     ],
                ],
            ],
        ];

        $securityCheckerMock = $this->getMockBuilder(SecurityChecker::class)->setMethods(['check'])->getMock();
        $securityCheckerMock->expects($this->any())->method('check')->will($this->returnValue(
            $empty ? [] : $mockOutput
        ));

        return $securityCheckerMock;
    }

    public function testUpdatesAreSaved()
    {
        $preCheck = SecurityAlert::get();
        $this->assertCount(0, $preCheck, 'database is empty to begin with');

        $this->runTask();

        $postCheck = SecurityAlert::get();
        $this->assertCount(6, $postCheck, 'SecurityAlert has been stored');
    }

    public function testNoDuplicates()
    {
        $this->runTask();

        $postCheck = SecurityAlert::get();
        $this->assertCount(6, $postCheck, 'SecurityAlert has been stored');

        $this->runTask();

        $postCheck = SecurityAlert::get();
        $this->assertCount(6, $postCheck, 'The SecurityAlert isn\'t stored twice.');
    }

    public function testSecurityAlertRemovals()
    {
        $this->runTask();

        $preCheck = SecurityAlert::get();
        $this->assertCount(6, $preCheck, 'database has stored SecurityAlerts');

        $securityCheckerMock = $this->getSecurityCheckerMock(true);
        $this->checkTask->setSecurityChecker($securityCheckerMock);

        $this->runTask();

        $postCheck = SecurityAlert::get();
        $this->assertCount(0, $postCheck, 'database is empty to finish with');
    }

    public function testIdentifierSetsFromTitleIfCVEIsNotSet()
    {
        $this->runTask();
        $frameworkAlert = SecurityAlert::get()
            ->filter('PackageName', 'silverstripe/framework')
            ->first();
        $this->assertNotEmpty($frameworkAlert->Identifier);
        $this->assertRegExp('/^SS-201[56]-\d{3}$/', $frameworkAlert->Identifier);
    }
}
