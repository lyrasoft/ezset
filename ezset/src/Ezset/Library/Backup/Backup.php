<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Library\Backup;

use Ezset\Library\Filesystem\FileFilter;

/**
 * The Backup class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Backup
{
	/**
	 * Property filter.
	 *
	 * @var  FileFilter
	 */
	protected static $filter;

	/**
	 * Property ignores.
	 *
	 * @var  array
	 */
	protected static $ignores = array(
		'*/.git/*',
		'/logs/*',
		'!/logs/index.html',
		'/log/*',
		'!/log/index.html',
		'/cache/*',
		'!/cache/index.html',
		'/tmp/*',
		'!/tmp/index.html',
		'/administrator/components/com_akeeba/backup/*.zip',
	);

	/**
	 * getBackupZipFile
	 *
	 * @return  string
	 */
	public static function getBackupZipFile()
	{
		return JPATH_ROOT . '/tmp/ezset/backup/ezset-backup-' . \JUri::getInstance()->getHost() . '.zip';
	}

	/**
	 * getBackupSQLFile
	 *
	 * @return  string
	 */
	public static function getBackupSQLFile()
	{
		return JPATH_ROOT . '/tmp/ezset/backup/ezset-sql-backup.sql';
	}

	/**
	 * getInstallationResource
	 *
	 * @return  string
	 */
	public static function getInstallationResource()
	{
		return EZSET_ROOT . '/resources/installation';
	}

	/**
	 * filter
	 *
	 * @param string $file
	 *
	 * @return  boolean
	 */
	public static function filter($file)
	{
		return static::getFileFilter()->test($file);
	}

	/**
	 * getFileFilter
	 *
	 * @return  FileFilter
	 */
	public static function getFileFilter()
	{
		if (!static::$filter)
		{
			static::$filter = new FileFilter(static::$ignores);
		}

		return static::$filter;
	}

	/**
	 * prepareBackupFolder
	 *
	 * @param string $path
	 *
	 * @return  void
	 */
	public static function prepareFolder($path)
	{
		// Create folder
		if (!\JFolder::exists($path))
		{
			\JFolder::create($path);
		}
	}

	/**
	 * writeHtaccess
	 *
	 * @param string $dest
	 *
	 * @return  void
	 */
	public static function writeHtaccess($dest)
	{
		if (is_file($dest))
		{
			return;
		}

		$htaccess = <<<HT
<IfModule !mod_authz_core.c>
Order deny,allow
Deny from all
</IfModule>
<IfModule mod_authz_core.c>
  <RequireAll>
    Require all denied
  </RequireAll>
</IfModule>
HT;

		if (!is_dir(dirname($dest)))
		{
			\JFolder::create(dirname($dest));
		}

		file_put_contents($dest, $htaccess);
	}

	/**
	 * redirectToDownload
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 */
	public static function redirectToDownload()
	{
		$username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
		$password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

		$uri = \JUri::getInstance(\JUri::root());

		$uri->setUser($username);
		$uri->setPass($password);
		$uri->setVar('cmd', 'backup.download');

		\JFactory::getApplication()->redirect($uri);

		exit();
	}
}
