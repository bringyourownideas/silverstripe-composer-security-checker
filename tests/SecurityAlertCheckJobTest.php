<?php

namespace BringYourOwnIdeas\SecurityChecker\Tests;

use BringYourOwnIdeas\SecurityChecker\Jobs\SecurityAlertCheckJob;
use BringYourOwnIdeas\SecurityChecker\Tasks\SecurityAlertCheckTask;
use SilverStripe\Dev\SapphireTest;
use Symbiote\QueuedJobs\Services\QueuedJobService;

class SecurityAlertCheckJobTest extends SapphireTest
{
    protected function setUp()
    {
        parent::setUp();

        QueuedJobService::config()->set('use_shutdown_function', false);
    }

    public function testJobCallsTask()
    {
        $spy = $this->getMockBuilder(SecurityAlertCheckTask::class)->setMethods(['run'])->getMock();
        $spy->expects($this->once())->method('run');

        $job = new SecurityAlertCheckJob;
        $job->setCheckTask($spy);

        $job->process();
    }
}
