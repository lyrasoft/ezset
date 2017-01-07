<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Library\Script;

use Windwalker\Script\AbstractScriptManager;

/**
 * The EzsetScript class.
 *
 * @since  __DEPLOY_VERSION__
 */
class EzsetScript extends AbstractScriptManager
{
	/**
	 * core
	 *
	 * @return  void
	 */
	public static function core()
	{
		if (!self::inited(__METHOD__))
		{
			$asset = static::getAsset('com_ezset');

			$asset->addJS('ezset.js');
			$asset->addJS('ezset-custom.js');
		}
	}
}
