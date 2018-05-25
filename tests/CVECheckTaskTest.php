<?php

use SensioLabs\Security\SecurityChecker;

class CVECheckTaskTest extends SapphireTest
{
    protected $usesDatabase = true;

    /**
     * provide a mock to remove dependency on external service
     */
    protected function getSecurityCheckerMock($empty = false)
    {
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
    }
}
CVENOTICE;

        $securityCheckerMock = $this->getMockBuilder(SecurityChecker::class)->setMethods(['check'])->getMock();
        $securityCheckerMock->expects($this->any())->method('check')->will($this->returnValue(
            $empty ? [] : json_decode($mockOutput, true)
        ));

        return $securityCheckerMock;
    }

    public function testUpdatesAreSaved()
    {
        $securityCheckerMock = $this->getSecurityCheckerMock();
        $checkTask = new CVECheckTask;
        $checkTask->setSecurityChecker($securityCheckerMock);

        $preCheck = CVE::get();
        $this->assertEquals(0, $preCheck->count(), 'database is empty to begin with');

        $checkTask->run(null);

        $postCheck = CVE::get();
        $this->assertEquals(1, $postCheck->count(), 'CVE has been stored');
    }

    public function testNoDuplicates()
    {
        $securityCheckerMock = $this->getSecurityCheckerMock();
        $checkTask = new CVECheckTask;
        $checkTask->setSecurityChecker($securityCheckerMock);

        $preCheck = CVE::get();
        $this->assertEquals(0, $preCheck->count(), 'database is empty to begin with');

        $checkTask->run(null);

        $postCheck = CVE::get();
        $this->assertEquals(1, $postCheck->count(), 'CVE has been stored');
        
        $checkTask->run(null);

        $postCheck = CVE::get();
        $this->assertEquals(1, $postCheck->count(), 'The CVE isn\'t stored twice.');
    }

    public function testCVERemovals()
    {
        $securityCheckerMock = $this->getSecurityCheckerMock();
        $checkTask = new CVECheckTask;
        $checkTask->setSecurityChecker($securityCheckerMock);

        $checkTask->run(null);

        $preCheck = CVE::get();
        $this->assertEquals(1, $preCheck->count(), 'database has stored CVEs');

        $securityCheckerMock = $this->getSecurityCheckerMock(true);
        $checkTask->setSecurityChecker($securityCheckerMock);

        $checkTask->run(null);

        $postCheck = CVE::get();
        $this->assertEquals(0, $postCheck->count(), 'database is empty to finish with');
    }
}
