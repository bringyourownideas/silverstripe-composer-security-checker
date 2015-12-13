<?php
/**
 * Composer security checker job. Runs the task which does the check as a queuedjob.
 *
 * @author Peter Thaleikis
 * @license MIT
 */
class CheckComposerSecurityJob extends AbstractQueuedJob implements QueuedJob
{
    /**
     * The task to run
     *
     * @var BuildTask
     */
    protected $task;

    /**
     * define the title
     *
     * @return string
     */
    public function getTitle()
    {
        return _t(
            'ComposerSecurity.Title',
            'Check if composer dependencies have known vulnerabilities.'
        );
    }

    /**
     * define the type.
     */
    public function getJobType()
    {
        $this->totalSteps = 1;

        return QueuedJob::QUEUED;
    }

    /**
     * init
     */
    public function setup()
    {
        // create the instance of the task
        $this->task = new CheckComposerSecurityTask();
    }

    /**
     * processes the task as a job
     */
    public function process()
    {
        // run the task
        $this->task->run(new SS_HTTPRequest());

        // mark job as completed
        $this->isComplete = true;
    }
}
