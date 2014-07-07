<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\System;

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
		if (! static::$orphan)
		{
			// Execute this object when system close.
			static::$orphan = new LanguageOrphan;
		}
	}
}
