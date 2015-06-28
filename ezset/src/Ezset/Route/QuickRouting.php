<?php
/**
 * Part of joomla341 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Route;

/**
 * The QuickRoute class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class QuickRouting
{
	/**
	 * route
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 */
	public static function route()
	{
		$app = \JFactory::getApplication();

		$input = $app->input;

		if ($app->isSite())
		{
			$closure = function(\JRouterSite $router, \JUri $uri) use ($input, $app)
			{
				$route = $uri->getPath();

				$route = trim($route, '/');

				// Admin
				if ($route == 'admin')
				{
					$uri = \JUri::getInstance();

					$target = new \JUri(\JUri::root() . 'administrator');

					$target->setQuery($uri->getQuery());

					$app->redirect($target);
				}

				return array();
			};

			$router = $app::getRouter();

			$router->attachParseRule($closure, JVERSION >= 3.4 ? $router::PROCESS_BEFORE : null);
		}
		else
		{
			if ($input->get('goezset') !== null)
			{
				$plugin = \JTable::getInstance('Extension');

				if ($plugin->load(array('name' => 'plg_system_ezset')))
				{
					$extId = $plugin->extension_id;

					$app->redirect(\JRoute::_('index.php?option=com_plugins&task=plugin.edit&extension_id=' . $extId, false));

					exit();
				}
			}
		}
	}
}
