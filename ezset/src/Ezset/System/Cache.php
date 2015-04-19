<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\System;

use Windwalker\Data\Data;

/**
 * Class Cache
 *
 * @since 1.0
 */
class Cache
{
	/**
	 * Detach whole page cache plugin in specific page.
	 *
	 * @return  int
	 */
	public static function manage()
	{
		$es            = \Ezset::getInstance();
		$input         = \JFactory::getApplication()->input;
		$control_type  = $es->params->get('cacheControlType', 'exclude');
		$cache_menus   = $es->params->get('CacheMenus', array());
		$cache_queries = $es->params->get('CacheQueries', array());
		$config        = \JFactory::getConfig();
		$cache         = null;

		$bool  = array();

		$itemid = $input->get('Itemid');

		if (in_array($itemid, $cache_menus))
		{
			$bool[] = true;
		}

		// Queries control
		$cache_queries = explode("\n", $cache_queries);

		foreach ($cache_queries as $q)
		{
			$q = explode('&', $q);
			$r = false;

			foreach ($q as $v)
			{
				$r = false;

				// Equals
				if (strpos($v, '!='))
				{
					$v = explode('!=', $v);

					if ($input->get(trim($v[0])) == trim($v[1]))
					{
						break;
					}
				}

				// Not equals
				else
				{
					$v = explode('=', $v);

					if ($input->get(trim($v[0])) != trim($v[1]))
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
			if (get_class($observer) == 'PlgSystemCache')
			{
				$cache = $observer;
			}
		}

		// Let's detach cache plugin
		if ($control_type == 'include')
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
	 * cacheEzsetData
	 *
	 * @param \PlgSystemEzset $ezset
	 *
	 * @return  void
	 */
	public static function cacheEzsetData(\PlgSystemEzset $ezset)
	{
		$cache = \JFactory::getCache('ezset', 'output');

		$id = static::getPageStoreId();

		if (!$cache->get($id))
		{
			$cache->store($ezset->data, $id);
		}
	}

	/**
	 * prepareEzsetData
	 *
	 * @param \PlgSystemEzset $ezset
	 *
	 * @return  void
	 */
	public static function prepareEzsetData(\PlgSystemEzset $ezset)
	{
		$cache = \JFactory::getCache('ezset', 'output');

		$id = static::getPageStoreId();

		$data = $cache->get($id);

		if ($data)
		{
			$ezset->data = new Data($data);
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

		return 'ezset-' . md5($uri);
	}
}
