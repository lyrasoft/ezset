<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\System;

use Ezset\Library\Language\LanguageOrphan;

/**
 * Class Language
 *
 * @since 1.0
 */
class Language
{
	/**
	 * Property orphan.
	 *
	 * @var  bool
	 */
	protected static $orphan = null;

	/**
	 * orphan
	 *
	 * @return  void
	 */
	public static function orphan()
	{
		$ezset = \Ezset::getInstance();

		if (!$ezset->params->get('system.development.LanguageOrphan'))
		{
			return;
		}

		if (!static::$orphan)
		{
			$path = JPATH_ROOT . '/' . $ezset->params->get('system.development.LanguageOrphan_Path', 'logs/languages.ini');

			// Execute this object when system close.
			static::$orphan = new LanguageOrphan($path);
		}
	}
}
