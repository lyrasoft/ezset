<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

const _JEXEC = 1;
error_reporting(32767);

define('INSTALL_ROOT', __DIR__);
define('JPATH_BASE', dirname(__DIR__));

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_LIBRARIES . '/import.legacy.php';
require_once JPATH_LIBRARIES . '/cms.php';

/**
 * The InstallationApplication class.
 *
 * @since  {DEPLOY_VERSION}
 */
class InstallationApplication extends JApplicationWeb
{
	/**
	 * Method to run the application routines.  Most likely you will want to instantiate a controller
	 * and execute it, or perform some sort of task directly.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		$page = $this->input->get('page', 'form');

		$this->set('title', 'ASIKART Joomla Installation');

		ob_start();

		include __DIR__ . '/page/' . $page . '.php';

		$content = ob_get_contents();

		ob_end_clean();

		include INSTALL_ROOT . '/tmpl/html.php';
	}
}

$app = new InstallationApplication;

$app->execute();