<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Ezset\Library\Provider;

use Joomla\DI\Container;
use Windwalker\DI\ServiceProvider;

// No direct access
defined('_JEXEC') or die;

/**
 * Ezset provider.
 *
 * @since 1.0
 */
class EzsetProvider extends ServiceProvider
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container $container The DI container.
	 *
	 * @return  Container  Returns itself to support chaining.
	 */
	public function register(Container $container)
	{
	}
}
