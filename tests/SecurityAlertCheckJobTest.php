<?php

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
