<?php
/**
 * @package        Asikart.Plugin
 * @subpackage     system.plg_ezset
 * @copyright      Copyright (C) 2014 SMS Taiwan, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Script file of Ezset Plugin.
 *
 * @since  1.0
 */
class PlgSystemEzsetInstallerScript
{
	/**
	 * Method to install the extension.
	 *
	 * @param \JInstallerAdapterPlugin $parent Parent installer adapter.
	 *
	 * @return  void
	 */
	public function install($parent)
	{
		$msg = '';

		// Enable plugin
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Reorder system plugins
		$query->update('#__extensions')
			->set('ordering = ordering + 1')
			->where($query->format('%n = %q', 'folder', 'system'));

		$db->setQuery($query)->execute();

		// Enable Ezset
		$query = $db->getQuery(true);

		$query->update('#__extensions')
			->set('enabled = 1')
			->set('ordering = 1')
			->where($query->format('%n = %q', 'element', 'ezset'))
			->where($query->format('%n = %q', 'folder', 'system'));

		$db->setQuery($query)->execute();

		$file_list = $this->copyFiles();

		if ($file_list)
		{
			$msg .= "<h3>成功複製檔案</h3><ul>{$file_list}</ul><br /><br />";
		}

		// Direct link
		$query->clear()
			->select('*')
			->from('#__extensions')
			->where($query->format('%n = %q', 'element', 'ezset'))
			->where($query->format('%n = %q', 'folder', 'system'));

		$plugin = $db->setQuery($query)->loadObject();

		$link = 'index.php?option=com_plugins&task=plugin.edit&extension_id=' . $plugin->extension_id;

		$msg = "<p>Easy set 安裝成功。</p><br />
				<h3><a href=\"{$link}\">[進入外掛管理]</a></h3><br /><br />" . $msg;

		echo $msg;
	}

	/**
	 * Method to uninstall the extension.
	 *
	 * @param \JInstallerAdapterPlugin $parent Parent installer adapter.
	 *
	 * @return  void
	 */
	public function uninstall($parent)
	{
	}

	/**
	 * Method to uninstall the extension.
	 *
	 * @param \JInstallerAdapterPlugin $parent Parent installer adapter.
	 *
	 * @return  void
	 */
	public function update($parent)
	{
		// $parent is the class calling this method
		$file_list = $this->copyFiles();

		$msg = '';

		if ($file_list)
		{
			$msg .= "<h3>成功複製更新檔案</h3><ul>{$file_list}</ul><br /><br />";
		}

		echo '<p>' . '更新成功' . '</p>' . $msg;
	}

	/**
	 * Method to run before an install/update/uninstall method.
	 *
	 * @param string                   $type   Install type (install, update, discover_install, extension_site).
	 * @param \JInstallerAdapterPlugin $parent Parent installer adapter.
	 *
	 * @return  void
	 */
	public function preflight($type, $parent)
	{
	}

	/**
	 * Method to run after an install/update/uninstall method.
	 *
	 * @param string                   $type   Install type (install, update, discover_install, extension_site).
	 * @param \JInstallerAdapterPlugin $parent Parent installer adapter.
	 *
	 * @return  void
	 */
	public function postflight($type, $parent)
	{
		$db = JFactory::getDbo();

		// Get install manifest
		// ========================================================================
		$p_installer = $parent->getParent();
		$installer   = new JInstaller;
		$manifest    = $p_installer->manifest;
		$path        = $p_installer->getPath('source');
		$result      = array();

		$css = <<<CSS
<style type="text/css">
#ak-install-img
{
}

#ak-install-msg
{
}
</style>
CSS;

		echo $css;

		$installScript = dirname($path) . '/windwalker/src/System/installscript.php';

		if (!is_file($installScript))
		{
			$installScript = JPATH_LIBRARIES . '/windwalker/src/System/installscript.php';
		}

		include $installScript;
	}

	/**
	 * _copyIncludeFiles
	 *
	 * @return  string
	 */
	protected function copyFiles()
	{
		$file_list = '';

		// ezset
		$types['events']['from'] = 'resources/ezset';
		$types['events']['to']   = 'ezset';

		foreach ($types as $type)
		{
			$include_path    = JPath::clean(JPATH_ROOT . '/plugins/system/ezset/' . $type['from']);
			$include_path_to = JPath::clean(JPATH_ROOT . '/' . $type['to']);
			$include_files   = JFolder::files($include_path, '.', true, true);

			if (!JFolder::exists($include_path_to))
			{
				JFolder::create($include_path_to);
			}

			foreach ($include_files as $include)
			{
				$include = JPath::clean($include);
				$file    = str_replace($include_path, $include_path_to, $include);

				if (!JFile::exists($file))
				{
					if (! is_dir(dirname($file)))
					{
						\JFolder::create(dirname($file));
					}

					JFile::copy($include, $file);
					$file_list .= '<li>' . $file . '</li>';
				}
			}
		}

		return $file_list;
	}
}