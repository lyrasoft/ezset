<?php
/**
 * Part of joomla341 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Route;

use Windwalker\Utilities\ArrayHelper;

/**
 * The QuickRoute class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Routing
{
	/**
	 * route
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 */
	public static function quickRouting()
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

	public static function ipBlock()
	{
		$ezset = \Ezset::getInstance();
		
		$blockType = $ezset->params->get('ipBlock', 0);
		
		if (!$blockType)
		{
			return;
		}

		$clients = $ezset->params->get('ipBlockClient');

		if ($ezset->app->isSite() && !in_array('site', $clients))
		{
			return;
		}

		if ($ezset->app->isAdmin() && !in_array('administrator', $clients))
		{
			return;
		}

		$ips = $ezset->params->get('ipBlockList');
		$ips = explode("\n", $ips);
		$ips = array_map('trim', $ips);
		$ips = static::addLocalhost($ips);

		$block = false;

		$currentIps = array(
			ArrayHelper::getValue($_SERVER, 'HTTP_CLIENT_IP'),
			ArrayHelper::getValue($_SERVER, 'HTTP_X_FORWARDED_FOR'),
			ArrayHelper::getValue($_SERVER, 'REMOTE_ADDR')
		);

		if (array_intersect($ips, $currentIps))
		{
			if ($blockType == 'deny')
			{
				$block = true;
			}
		}
		else
		{
			if ($blockType == 'allow')
			{
				$block = true;
			}
		}

		$input = \JFactory::getApplication()->input;

		if ($ezset->app->isAdmin())
		{
			if (isset($_GET[$ezset->params->get('adminSecureCode')])  || ($input->get('task') == 'login' && $input->get('option') == 'com_login'))
			{
				$block = false;
			}
		}

		if (!\JFactory::getUser()->guest && $ezset->app->isAdmin())
		{
			$block = false;
		}

		if ($block)
		{
			header('HTTP/1.1 404');
			die;
		}
	}

	/**
	 * addLocalhost
	 *
	 * @param   array  $ips
	 *
	 * @return  array
	 */
	public static function addLocalhost($ips)
	{
		$locals = array(
			'localhost',
			'127.0.0.1',
			'::1',
			'fe80::1'
		);

		foreach ($ips as $ip)
		{
			if (in_array($ip, $locals))
			{
				$ips = $ips + $locals;

				return array_unique($ips);
			}
		}

		return $ips;
	}
}
