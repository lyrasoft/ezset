<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Ezset\Library\Router;

use Windwalker\Router\RadRoute;

// No direct access
defined('_JEXEC') or die;

/**
 * Ezset route.
 *
 * @since 1.0
 */
class Route extends RadRoute
{
	/**
	 * Property defaultOption.
	 *
	 * @var  string
	 */
	protected static $defaultOption = 'com_ezset';
}
