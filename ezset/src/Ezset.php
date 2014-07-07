<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Class Ezset
 *
 * @since 1.0
 */
class Ezset
{
	/**
	 * Get Easyset Instance.
	 *
	 * @return PlgSystemEzset
	 */
	public static function getInstance()
	{
		return PlgSystemEzset::getInstance();
	}

	/**
	 * Detect is this page are frontpage?
	 *
	 * @return  boolean Is frontpage?
	 */
	public static function isHome()
	{
		$uri  = \JUri::getInstance();
		$root = $uri::root(true);

		// Get site route
		$route = \JString::substr($uri->getPath(), \JString::strlen($root));

		// Remove index.php
		$route = str_replace('index.php', '', $route);

		if (! trim($route, '/') && ! $uri->getVar('option'))
		{
			return true;
		}

		return false;
	}
}
