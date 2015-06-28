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
		$uri = new \JUri(\JUri::root());

		$uri->setScheme(null);

		return JPATH_ROOT . '/tmp/ezset/backup/ezset-backup-' . \JFilterOutput::stringURLSafe($uri) . '.zip';
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
	 * encode
	 *
	 * @param string $file
	 *
	 * @return  string
	 */
	public static function encode($file)
	{
		$file = urlencode($file);

		return str_replace(array('%5C', '%2F'), DIRECTORY_SEPARATOR, $file);
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
			static::$filter = new FileFilter(static::getIgnores());
		}

		return static::$filter;
	}

	/**
	 * getIgnores
	 *
	 * @return  array
	 */
	protected static function getIgnores()
	{
		// Get custom ignores
		$ignoreFile = new \SplFileInfo(EZSET_FRONT . '/cmd/backupignore');

		$ignores = array();

		if (is_file($ignoreFile->getPathname()))
		{
			$ignores = file_get_contents($ignoreFile->getPathname());

			$ignores = array_filter(array_map('trim', explode("\n", $ignores)), 'strlen');

			$ignores = array_filter($ignores, array(__CLASS__, 'notComment'));
		}

		return array_merge(static::$ignores, $ignores);
	}

	/**
	 * isComment
	 *
	 * @param string $line
	 *
	 * @return  bool
	 */
	public static function notComment($line)
	{
		if (!strlen($line))
		{
			return true;
		}

		$line = trim($line);

		return $line[0] != '#';
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

		$input = \JFactory::getApplication()->input;

		$uri = new \JUri(\JUri::root());

		$uri->setUser($username);
		$uri->setPass($password);
		$uri->setVar('access_token', $input->get('access_token'));
		$uri->setVar('cmd', 'backup.download');

		\JFactory::getApplication()->redirect($uri);

		exit();
	}

	/**
	 * fix
	 *
	 * @param string $path
	 *
	 * @return  array
	 */
	public static function fix($path = null)
	{
		$path = $path ? : JPATH_ROOT;

		$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

		$dirs = array();
		$fixed = array();

		/** @var \SplFileInfo $item */
		foreach ($iterator as $item)
		{
			$relative = str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', \JPath::clean($item->getPathname()));

			if (!static::hasUTF8($relative))
			{
				continue;
			}

			if ($item->isDir())
			{
				$dirs[] = $item;

				continue;
			}

			$relative = urldecode($relative);

			$dest = new \SplFileInfo(JPATH_ROOT . DIRECTORY_SEPARATOR . $relative);

			if (!is_dir($dest->getPath()))
			{
				\JFolder::create($dest->getPath());
			}

			$fixed[] = $item->getPathname() . ' => ' . $dest->getPathname();

			\JFile::move($item->getPathname(), $dest->getPathname());
		}

		/** @var \SplFileInfo $dir */
		foreach ($dirs as $dir)
		{
			if (is_dir($dir->getPathname()))
			{
				\JFolder::delete($dir->getPathname());
			}
		}

		return $fixed;
	}

	/**
	 * hasUTF8
	 *
	 * @param string $file
	 *
	 * @return  boolean
	 */
	public static function hasUTF8($file)
	{
		\JPath::clean($file);

		$file = str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', $file);

		return strpos($file, '%') !== false;
	}
}
