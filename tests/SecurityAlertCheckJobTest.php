<?php

namespace BringYourOwnIdeas\SecurityChecker\Tests;

use BringYourOwnIdeas\SecurityChecker\Tasks\SecurityAlertCheckTask;
use BringYourOwnIdeas\SecurityChecker\Jobs\SecurityAlertCheckJob;
use SilverStripe\Dev\SapphireTest;

class SecurityAlertCheckJobTest extends SapphireTest
{
    public function testJobCallsTask()
    {
        $spy = $this->getMockBuilder(SecurityAlertCheckTask::class)->setMethods(['run'])->getMock();
        $spy->expects($this->once())->method('run');

        $job = new SecurityAlertCheckJob;
        $job->setCheckTask($spy);

        $job->process();
    }
}
