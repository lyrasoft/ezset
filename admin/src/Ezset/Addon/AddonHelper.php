<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Addon;

use Windwalker\Filesystem\Filesystem;
use Windwalker\Filesystem\Path\PathCollection;
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
				'class' => 'EzsetAddon' . StringNormalise::toCamelCase($name),
				'client' => 'site',
			);
		}

		return $addons;
	}

	/**
	 * loadAddons
	 *
	 * @return  AbstractAddon[]
	 */
	public static function loadAddons()
	{
		if (static::$addons === null)
		{
			static::$addons = array();

			$path = PathHelper::getAdmin('com_ezset') . '/addon';

			$folders = \JFolder::folders($path);

			foreach ($folders as $folder)
			{
				static::$addons[$folder] = static::loadAddon($folder);
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
		static::loadAddons();

		if (isset(static::$addons[$addon]))
		{
			return static::$addons[$addon];
		}

		return false;
	}

	/**
	 * loadAddon
	 *
	 * @param  string $name
	 *
	 * @return  AbstractAddon
	 * @throws \RuntimeException
	 */
	protected static function loadAddon($name)
	{
		$class = 'EzsetAddon' . ucfirst($name);

		if (!class_exists($class))
		{
			include_once PathHelper::getAdmin('com_ezset') . '/addon/' . $name . '/' . $name . '.php';
		}

		/** @var AbstractAddon $instance */
		$instance = new $class;
		$instance->register();

		return $instance;
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
