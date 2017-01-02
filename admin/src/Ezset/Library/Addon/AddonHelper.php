<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Library\Addon;

use Ezset\Library\Addon\AbstractAddon;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Filesystem\Filesystem;
use Windwalker\Helper\PathHelper;
use Windwalker\String\StringNormalise;

/**
 * The AddonHelper class.
 *
 * @since  __DEPLOY_VERSION__
 */
class AddonHelper
{
	/**
	 * Property addons.
	 *
	 * @var  AbstractAddon[]
	 */
	protected static $addons = null;

	/**
	 * findAddons
	 *
	 * @return  array
	 */
	public static function findAddons()
	{
		$addons = array();

		/** @var \SplFileInfo $path */
		foreach (Filesystem::folders(PathHelper::getAdmin('com_ezset') . '/addon') as $path)
		{
			$name = $path->getBasename();

			$addons[$name] = (object) array(
				'name' => $name,
				'path' => '{ROOT}' . substr($path->getPath(), strlen(JPATH_ROOT)),
				'class' => 'EzsetAddon' . StringNormalise::toCamelCase($name),
				'client' => 'admin',
			);
		}

		/** @var \SplFileInfo $path */
		foreach (Filesystem::folders(JPATH_ROOT . '/ezset/addon') as $path)
		{
			$name = $path->getBasename();
			
			$addons[$name] = (object) array(
				'name' => $name,
				'path' => '{ROOT}' . substr($path->getPath(), strlen(JPATH_ROOT)),
				'class' => static::getAddonClass(StringNormalise::toCamelCase($name)),
				'client' => 'site',
			);
		}

		return $addons;
	}

	/**
	 * loadAddons
	 *
	 * @return  AbstractAddon[]
	 * @throws \RuntimeException
	 */
	public static function getAddons()
	{
		if (static::$addons === null)
		{
			static::$addons = array();

			$mapper = new DataMapper('#__ezset_addons');

			$addons = $mapper->find(array('state > 0'), 'ordering');

			foreach ($addons as $addon)
			{
				$found = static::loadAddon($addon->dump());

				if ($found)
				{
					static::$addons[$addon->name] = $found;
				}
			}
		}

		return static::$addons;
	}

	/**
	 * getAddon
	 *
	 * @param string $addon
	 *
	 * @return  bool|AbstractAddon
	 */
	public static function getAddon($addon)
	{
		static::getAddons();

		if (isset(static::$addons[$addon]))
		{
			return static::$addons[$addon];
		}

		return false;
	}

	/**
	 * loadAddon
	 *
	 * @param  array $addon
	 *
	 * @return  AbstractAddon|bool
	 * @throws \RuntimeException
	 */
	protected static function loadAddon($addon)
	{
		$name = $addon['name'];
		$class = static::getAddonClass($name);

		if (!class_exists($class))
		{
			$file = PathHelper::getAdmin('com_ezset') . '/addon/' . $name . '/' . $name . '.php';

			if (is_file($file))
			{
				include_once $file;
			}
		}

		if (!class_exists($class))
		{
			return false;
		}

		return new $class($addon);
	}

	/**
	 * getAddonClass
	 *
	 * @param   string  $name
	 *
	 * @return  string
	 */
	protected static function getAddonClass($name)
	{
		return 'EzsetAddon' . ucfirst($name);
	}

	/**
	 * getFieldsetDescriptionFile
	 *
	 * @param string $addon
	 * @param string $group
	 * @param string $fieldset
	 *
	 * @return  bool|string
	 */
	public static function getFieldsetDescriptionFile($addon, $group, $fieldset)
	{
		$instance = static::getAddon($addon);

		$file = $instance::getDir('description/' . $group . '/' . $fieldset . '.php');

		if (is_file($file))
		{
			return $file;
		}

		return false;
	}
}
