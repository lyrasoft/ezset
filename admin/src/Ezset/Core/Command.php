<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Core;

use Ezset\Library\Addon\AddonHelper;

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

		$option = $input->get('option');

		if ($option)
		{
			return;
		}

		if ($app->isSite() && !\Ezset::isHome())
		{
			return;
		}

		$cmd = trim($input->getCmd('cmd'));

		if ($cmd)
		{
			$cmd  = str_replace('.', '/', $cmd);
			$file = EZSET_FRONT . '/cmd/' . $cmd . '.php';

			if (!file_exists($file))
			{
				$addons = AddonHelper::getAddons();

				foreach ($addons as $addon)
				{
					$file = $addon::getDir('cmd/' . $cmd . '.php');

					if (file_exists($file))
					{
						break;
					}
				}
			}

			if (file_exists($file))
			{
				include $file;
			}

			die;
		}
	}
}
