<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\System;

use Windwalker\Registry\Registry;

/**
 * Class Cache
 *
 * @since 1.0
 */
class Cache
{
	const CONTROL_TYPE_EXCLUDE = 'exclude';
	const CONTROL_TYPE_INCLUDE = 'include';

	/**
	 * Detach whole page cache plugin in specific page.
	 *
	 * @return  int
	 */
	public static function manage()
	{
		$ezset        = \Ezset::getInstance();
		$controlType  = $ezset->params->get('CacheControl_Type', static::CONTROL_TYPE_EXCLUDE);
		$cacheMenus   = $ezset->params->get('CacheControl_Menus', array());
		$cacheQueries = $ezset->params->get('CacheControl_Queries', '');
		$config       = \JFactory::getConfig();
		$cache        = null;

		$bool  = array();

		$itemid = $ezset->input->get('Itemid');

		if (in_array($itemid, $cacheMenus))
		{
			$bool[] = true;
		}

		// Queries control
		$cacheQueries = explode("\n", $cacheQueries);

		foreach ($cacheQueries as $q)
		{
			$q = explode('&', $q);
			$r = false;

			foreach ($q as $v)
			{
				$r = false;

				if (!$v)
				{
					continue;
				}

				// Equals
				if (strpos($v, '!='))
				{
					$v = explode('!=', $v);

					if ($ezset->input->get(trim($v[0])) == trim($v[1]))
					{
						break;
					}
				}

				// Not equals
				else
				{
					$v = explode('=', $v);

					if ($ezset->input->get(trim($v[0])) != trim($v[1]))
					{
						break;
					}
				}

				$r = true;
			}

			$bool[] = $r ? true : false;
		}

		$tmp = false;

		foreach ($bool as $v)
		{
			if ($v)
			{
				$tmp = true;
				break;
			}
		}

		$bool = $tmp;

		// Determine cache plugin
		$dispatcher = \JEventDispatcher::getInstance();
		$observers  = $dispatcher->get('_observers');

		foreach ($observers as $observer)
		{
			if (is_object($observer) && get_class($observer) === 'PlgSystemCache')
			{
				$cache = $observer;
			}
		}

		// Let's detach cache plugin
		if ($controlType === static::CONTROL_TYPE_INCLUDE)
		{
			if ($bool == true)
			{
				$caching = 2;
			}
			else
			{
				$caching = 0;
				$dispatcher->detach($cache);
			}
		}
		else
		{
			if ($bool == true)
			{
				$caching = 0;
				$dispatcher->detach($cache);
			}
			else
			{
				$caching = 2;
			}
		}

		$config->set('caching', $caching);

		return $caching;
	}

	/**
	 * Cache Ezset data to make sure if won't effected by Joomla cache.
	 *
	 * @param \Ezset $ezset
	 *
	 * @return  void
	 */
	public static function cacheEzsetData(\Ezset $ezset)
	{
		$cache = \JFactory::getCache('ezset', 'output');

		$id = static::getPageStoreId();

		if (!$cache->get($id))
		{
			$cache->store($ezset->data->toArray(), $id);
		}
	}

	/**
	 * Cache Ezset data to make sure if won't effected by Joomla cache.
	 *
	 * @param \Ezset $ezset
	 *
	 * @return  void
	 */
	public static function prepareEzsetData(\Ezset $ezset)
	{
		$cache = \JFactory::getCache('ezset', 'output');

		$id = static::getPageStoreId();

		$data = $cache->get($id);

		if ($data)
		{
			$ezset->setData(new Registry($data));
		}
	}

	/**
	 * getPageStoreId
	 *
	 * @return  string
	 */
	public static function getPageStoreId()
	{
		$uri = \JUri::getInstance()->toString();

		$app = \JFactory::getApplication();

		return 'ezset-' . md5($app->get('secret') . $uri);
	}
}
