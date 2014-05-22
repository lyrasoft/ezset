<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Helper;

/**
 * Class UriHelper
 *
 * @since 1.0
 */
class UriHelper
{
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

	/**
	 * Give a relative path, return path with host.
	 *
	 * @param   string $path A system path.
	 *
	 * @return  string  Path with host added.
	 */
	public static function pathAddHost($path)
	{
		if (!$path)
		{
			return '';
		}

		// Build path
		$uri = new \JURI($path);

		if ($uri->getHost())
		{
			return $path;
		}

		$uri->parse(\JURI::root());
		$root_path = $uri->getPath();

		if (strpos($path, $root_path) === 0)
		{
			$num  = \JString::strlen($root_path);
			$path = \JString::substr($path, $num);
		}

		$uri->setPath($uri->getPath() . $path);
		$uri->setScheme('http');
		$uri->setQuery(null);

		return $uri->toString();
	}
}
