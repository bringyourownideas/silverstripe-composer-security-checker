<?php

class CVECheckJobTest extends SapphireTest
{
    public function testJobCallsTask()
    {
        $spy = $this->getMockBuilder(CVECheckTask::class)->setMethods(['run'])->getMock();
        $spy->expects($this->once())->method('run');

        $job = new CVECheckJob;
        $job->setCheckTask($spy);

        $job->process();
    }
}
