<?php

namespace BringYourOwnIdeas\SecurityChecker\Jobs;

use BringYourOwnIdeas\SecurityChecker\Tasks\SecurityAlertCheckTask;
use Symbiote\QueuedJobs\Services\QueuedJob;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;

/**
 * Composer security checker job. Runs the task which does the check as a queuedjob.
 *
 * @author Peter Thaleikis
 * @license BSD-3-Clause
 */
class SecurityAlertCheckJob extends AbstractQueuedJob implements QueuedJob
{
    private static $dependencies = [
        'checkTask' => '%$' . SecurityAlertCheckTask::class,
    ];

    /**
     * @var SecurityAlertCheckTask
     */
    protected $checkTask;

    /**
     * @return SecurityAlertCheckTask
     */
    public function getCheckTask()
    {
        return $this->checkTask;
    }

    /**
     * @param SecurityAlertCheckTask $checkTask
     * @return SecurityAlertCheckJob
     */
    public function setCheckTask(SecurityAlertCheckTask $checkTask)
    {
        $this->checkTask = $checkTask;
        return $this;
    }

    public function getTitle()
    {
        return _t(
            __CLASS__ . '.Title',
            'Check if any composer managed modules have known security vulnerabilities.'
        );
    }

    public function getJobType()
    {
        $this->totalSteps = 1;

        return QueuedJob::QUEUED;
    }

    public function process()
    {
        // run the task
        $task = $this->getCheckTask();
        $task->run(null);

        // mark job as completed
        $this->isComplete = true;
    }
}
