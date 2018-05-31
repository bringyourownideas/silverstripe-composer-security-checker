<?php

use SensioLabs\Security\SecurityChecker;

/**
 * Checks if there are any insecure dependencies.
 */
class SecurityAlertCheckTask extends BuildTask
{
    /**
     * @var SecurityChecker
     */
    protected $securityChecker;

    private static $dependencies = [
        'SecurityChecker' => '%$' . SecurityChecker::class,
    ];

    protected $title = 'Composer security checker';

    protected $description =
        'Checks if any modules managed through composer have known security vulnerabilities at the used version.';

    /**
     * @return SecurityChecker
     */
    public function getSecurityChecker()
    {
        return $this->securityChecker;
    }

    /**
     * @param SecurityChecker $securityChecker
     * @return $this
     */
    public function setSecurityChecker(SecurityChecker $securityChecker)
    {
        $this->securityChecker = $securityChecker;
        return $this;
    }

    /**
     * Most SilverStripe issued alerts are _not_ assiged CVEs.
     * However they have their own identifier in the form of a
     * prefix to the title - we can use this instead of a CVE ID.
     *
     * @param string $cve
     * @param string $title
     *
     * @return string
     */
    protected function discernIdentifier($cve, $title)
    {
        $identifier = $cve;
        if (!$identifier || $identifier === '~') {
            $identifier = explode(':', $title);
            $identifier = array_shift($identifier);
        }
        $this->extend('updateIdentifier', $identifier, $cve, $title);
        return $identifier;
    }

    public function run($request)
    {
        // to keep the list up to date while removing resolved issues we keep all of found issues
        $validEntries = array();

        // use the security checker of
        $checker = $this->getSecurityChecker();
        $alerts = $checker->check(BASE_PATH . DIRECTORY_SEPARATOR . 'composer.lock');

        // go through all alerts for packages - each can contain multiple issues
        foreach ($alerts as $package => $packageDetails) {
            // go through each individual known security issue
            foreach ($packageDetails['advisories'] as $details) {
                $identifier = $this->discernIdentifier($details['cve'], $details['title']);
                // check if this vulnerability is already known
                $vulnerability = SecurityAlert::get()->filter(array(
                    'PackageName' => $package,
                    'Version' => $packageDetails['version'],
                    'Identifier'   => $identifier,
                ));

                // Is this vulnerability known? No, lets add it.
                if ((int) $vulnerability->count() === 0) {
                    $vulnerability = SecurityAlert::create();
                    $vulnerability->PackageName  = $package;
                    $vulnerability->Version      = $packageDetails['version'];
                    $vulnerability->Title        = $details['title'];
                    $vulnerability->ExternalLink = $details['link'];
                    $vulnerability->Identifier   = $identifier;

                    $vulnerability->write();

                    // add the new entries to the list of valid entries
                    $validEntries[] = $vulnerability->ID;
                } else {
                    // add existing vulnerabilities (probably just 1) to the list of valid entries
                    $validEntries = array_merge($validEntries, $vulnerability->column('ID'));
                }
                
                if ($vulnerability->hasExtension(SecurityAlertExtension::class) &&
                    $vulnerability->PackageRecordID === 0 &&
                    $packageRecord = Package::get()->find('Name', $package)
                ) {
                    $vulnerability->PackageRecordID = $packageRecord->ID;
                }
            }
        }

        // remove all entries which are resolved (no longer $validEntries)
        $removeOldSecurityAlerts = SQLDelete::create('"SecurityAlert"');
        if (empty($validEntries)) {
            // There were no SecurityAlerts listed for our installation - so flush any old data
            $removeOldSecurityAlerts->execute();
        } else {
            $removable = SecurityAlert::get()->exclude(array('ID' => $validEntries));
            // Be careful not to remove all SecurityAlerts on the case that every entry is valid
            if ($removable->exists()) {
                // SQLConditionalExpression does not support IN() syntax via addWhere
                // so we have to build this up manually
                $convertIDsToQuestionMarks = function ($id) {
                    return '?';
                };
                $queryArgs = $removable->column('ID');
                $paramPlaceholders = implode(',', array_map($convertIDsToQuestionMarks, $queryArgs));

                $removeOldSecurityAlerts = $removeOldSecurityAlerts->addWhere([
                    '"ID" IN(' . $paramPlaceholders . ')' => $queryArgs
                ]);
                $removeOldSecurityAlerts->execute();
            }
        }

        // notify that the task finished.
        $this->output('The task finished running. You can find the updated information in the database now.');
    }

    /**
     * prints a message during the run of the task
     *
     * @param string $text
     */
    protected function output($text)
    {
        if (!SapphireTest::is_running_test()) {
            echo Director::is_cli() ? $text . PHP_EOL : "<p>$text</p>\n";
        }
    }
}
