<?php
/**
 * Checks if there are any dependencies to are insecure.
 */
use SensioLabs\Security\SecurityChecker;

class SecurityCheckerTask extends BuildTask {
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
	public function run($request) {
		if (Permission::check('ADMIN') !== true && !$this->isCLI()) {
			$this->message('Only admins can run this task.');
		} else {
			// use the security checker of
			$checker = new SecurityChecker();
			$alerts = $checker->check($this->getPathToComposerlock());

			// are there any issues known?
			if (is_array($alerts) && empty($alerts)) {
				$this->message('No known security vulnerabilities have been found.');
			} else {
				$this->message('The following modules have known security vulnerabilities');
				foreach ($alerts as $package => $packageDetails) {
					// write the heading and then list all known advisories
					$this->message(sprintf(
						'%s v%s: ',
						$package,
						$packageDetails['version']
					));

					// list all known issues
					foreach ($packageDetails['advisories'] as $file => $details) {
						$this->message(sprintf(
							'%s (%s %s)',
							$details['title'],
							$this->link($details['link'], 'Source'),
							($details['cve'] == '') ? '' : $this->link(
								'https://cve.mitre.org/cgi-bin/cvename.cgi?name=' . $details['cve'],
								$details['cve']
							)
						));
					}
				}
			}

			$this->message('The task finished running.');
		}
	}

	/**
	 * @var boolean
	 */
	protected function isCLI() {
		return (PHP_SAPI === 'cli');
	}

	/**
	 * @return string
	 */
	protected function getPathToComposerlock() {
		return (($this->isCLI()) ? '..' : $_SERVER['DOCUMENT_ROOT']) . '/composer.lock';
	}

	/**
	 * create an a tag
	 *
	 * @param string $href
	 * @param string $text
	 * @return string
	 */
	protected function link($href, $text) {
		return $this->isCLI() ? $href : '<a href="' . $href . '" target="_blank">' . $text . '</a>';
	}

	/**
	 * prints a message during the run of the task
	 *
	 * @param string $text
	 */
	protected function message($text) {
		if(!$this->isCLI()) $text = '<p>' . $text . '</p>' . PHP_EOL;

		echo $text . PHP_EOL;
	}
}
