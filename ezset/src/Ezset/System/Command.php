<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\System;

/**
 * Class Command
 *
 * @since 1.0
 */
class Command
{
	/**
	 * execute
	 *
	 * @return  void
	 */
	public static function execute()
	{
		$app = \JFactory::getApplication();
		$input = $app->input;

		if ($app->isAdmin())
		{
			return;
		}

		$cmd = $input->getCmd('cmd');

		if ($cmd)
		{
			$cmd  = str_replace('.', '/', $cmd);
			$file = EZSET_FRONT . '/cmd/' . $cmd . '.php';

			if (!file_exists($file))
			{
				$file = EZSET_ROOT . '/cmd/' . $cmd . '.php';
			}

			if (file_exists($file))
			{
				include $file;
			}

			jexit();
		}
	}
}
