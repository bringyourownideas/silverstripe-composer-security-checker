<?php
/**
 * Checks if there are any insecure dependencies.
 */
use SensioLabs\Security\SecurityChecker;

class CheckComposerSecurityTask extends BuildTask
{
    /**
     * @var string
     */
    protected $title = 'Composer security checker';

    /**
     * @var string
     */
    protected $description = 'Checks if any composer dependencies has known security vulnerabilities.';

    /**
     * @param SS_HTTPRequest $request
     */
    public function run($request)
    {
        // to keep the list up to date while removing resolved issues we keep all ids of again encountered issues
        $remainingEntries = array();

        // use the security checker of
        $checker = new SecurityChecker();
        $alerts = $checker->check($this->getPathToComposerlock());

        // Are there any issues known? If so save the information in the database
        if (is_array($alerts) && !empty($alerts)) {
            // go through all alerts for packages - each can contain multiple issues
            foreach ($alerts as $package => $packageDetails) {
                // go through each individual known security issue
                foreach ($packageDetails['advisories'] as $details) {
                    // check if this vulnerability is already known
                    $vulnerability = ComposerSecurityVulnerability::get()->filter(array(
                        'Package'    => $package,
                        'Version'    => $packageDetails['version'],
                        'Title'        => $details['title'],
                    ));

                    // Is this vulnerability known? No, lets add it.
                    if ((int) $vulnerability->count() === 0) {
                        $vulnerability = new ComposerSecurityVulnerability();
                        $vulnerability->Package            = $package;
                        $vulnerability->Version            = $packageDetails['version'];
                        $vulnerability->Title            = $details['title'];
                        $vulnerability->ExternalLink    = $details['link'];
                        $vulnerability->CVE                = $details['cve'];
                        $vulnerability->write();

                        // add the new entries to the list of the remaining entries
                        $remainingEntries[] = $vulnerability->ID;
                    } else {
                        // add all matching known vulnerabilities - this way we can keep those.
                        $remainingEntries = array_merge($remainingEntries, $vulnerability->column('ID'));
                    }
                }
            }
        }

        // remove all entries which are resolved (not in $remainingEntries)
        foreach (ComposerSecurityVulnerability::get()->exclude(array('ID' => $remainingEntries)) as $vulnerability) {
            $vulnerability->delete();
        }

        // notify that the task finished.
        $this->message('The task finished running. You can find the updated information in the database now.');
    }

    /**
     * @var boolean
     */
    protected function isCLI()
    {
        return (PHP_SAPI === 'cli');
    }

    /**
     * @return string
     */
    protected function getPathToComposerlock()
    {
        return (($this->isCLI()) ? '..' : $_SERVER['DOCUMENT_ROOT']) . '/composer.lock';
    }

    /**
     * prints a message during the run of the task
     *
     * @param string $text
     */
    protected function message($text)
    {
        if (!$this->isCLI()) {
            $text = '<p>' . $text . '</p>' . PHP_EOL;
        }

        echo $text . PHP_EOL;
    }
}
