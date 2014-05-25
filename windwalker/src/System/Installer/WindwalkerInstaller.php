<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Windwalker\System\Installer;

/**
 * Class WindwalkerInstaller
 *
 * @since 1.0
 */
class WindwalkerInstaller
{
	/**
	 * The bin file content.
	 *
	 * @var  string
	 */
	static protected $binFile = <<<BIN
#!/usr/bin/env php
<?php

include_once dirname(__DIR__) . '/libraries/windwalker/bin/windwalker.php';

BIN;

	/**
	 * createBinFile
	 *
	 * @return  void
	 */
	public static function createBinFile()
	{
		$windPath = getcwd();

		file_put_contents($windPath . '/../../bin/windwalker', static::$binFile);
	}

	/**
	 * copyConfigFile
	 *
	 * @return  void
	 */
	public static function copyConfigFile()
	{
		$windPath = getcwd();

		if (! is_file($windPath . '/config.dist.json'))
		{
			copy($windPath . '/config.dist.json', $windPath . '/config.json');
		}
	}

	/**
	 * createBundleDir
	 *
	 * @return  bool
	 */
	public static function createBundleDir()
	{
		$windPath = getcwd();

		$bundlesDir = dirname($windPath) . '/windwalker-bundles';

		if (! is_dir($bundlesDir))
		{
			mkdir($bundlesDir);

			file_put_contents($bundlesDir . '/index.html', '<!DOCTYPE html><title></title>');

			return true;
		}

		return false;
	}

	/**
	 * install
	 *
	 * @return  void
	 */
	public static function install()
	{
		static::createBinFile();

		static::copyConfigFile();

		static::createBundleDir();
	}
}
 