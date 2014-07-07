<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

if (! class_exists('JRegistry'))
{
	/**
	 * Class JRegistry
	 *
	 * @since 1.0
	 */
	class JRegistry extends \Joomla\Registry\Registry
	{}
}

if (! class_exists('JInput'))
{
	/**
	 * Class JInput
	 *
	 * @since 1.0
	 */
	class JInput extends \Joomla\Input\Input
	{}
}
