<?php
/**
 * Part of joomla330 project. 
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
		$uri = \JUri::getInstance();
		$current = $uri->toString();

		if (\JUri::base() == $current || trim(\JUri::base(), '/') . '/index.php' == $current)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
