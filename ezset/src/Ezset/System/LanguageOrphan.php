<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\System;

/**
 * Class LanguageOrphan
 *
 * @since 1.0
 */
class LanguageOrphan
{
	/**
	 * Class destructor.
	 */
	public function __destruct()
	{
		// Get some data
		$lang    = \JFactory::getLanguage();
		$orphans = $lang->getOrphans();
		$used    = $lang->getUsed();
		$es      = \Ezset::getInstance();

		// Get file
		$path = JPATH_ROOT . '/' . $es->params->get('languageOrphanPath', 'logs/language.ini');
		$path = \JPath::clean($path);
		$file = '';

		if (\JFile::exists($path))
		{
			$file = file_get_contents($path);
		}

		// Set ini into registry, then convert to object
		$old = new \JRegistry;
		$old->loadString($file, 'INI', array('processSections' => 'true'));
		$old = $old->toObject();

		// Remove translated key
		foreach ((array) $old as $k => $v)
		{
			foreach ((array) $v as $k2 => $v2)
			{
				if (array_key_exists($k2, $used))
				{
					unset($old->$k->$k2);

					if (!$old->$k)
					{
						unset($old->$k);
					}
				}
			}
		}

		// Get orphan keys
		$obj = new \JObject;

		foreach ($orphans as $k => $v)
		{
			$key = explode('_', $k);

			$context = array_slice($key, 0, 2);
			$context = implode('_', $context);

			$lang = array_slice($key, 2);
			$lang = implode(' ', $lang);

			if (!$obj->get($context))
			{
				$obj->set($context, new \JObject);
			}

			$obj->$context->set($k, $lang);
		}

		// Merge ini and orphans
		$ini = $obj;

		foreach ((array) $old as $k => $v)
		{
			if (isset($ini->$k))
			{
				$ini->$k = (object) array_merge((array) $ini->$k, (array) $old->$k);
			}
			else
			{
				$ini->$k = $v;
			}

			$ini->$k = (array) $ini->$k;
			ksort($ini->$k);
			$ini->$k = (object) $ini->$k;
		}

		// Save to file
		$ini = new \JRegistry($ini);
		$ini = $ini->toString('ini');
		$ini = str_replace('_errors=""', '', $ini);
		$ini = str_replace('_errors=', '', $ini);

		@\JFile::write($path, $ini);
	}
}
