<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Script file of HelloWorld component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_ezset
 */
class Com_EzsetInstallerScript
{
	/**
	 * Method to install the component.
	 *
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function install(\JInstallerAdapterComponent $parent)
	{
	}

	/**
	 * Method to uninstall the component.
	 *
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function uninstall(\JInstallerAdapterComponent $parent)
	{
	}

	/**
	 * Method to update the component
	 *
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function update(\JInstallerAdapterComponent $parent)
	{
	}

	/**
	 * ethod to run before an install/update/uninstall method
	 *
	 * @param string                     $type
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function preflight($type, \JInstallerAdapterComponent $parent)
	{
	}

	/**
	 * Method to run after an install/update/uninstall method
	 *
	 * @param string                     $type
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function postflight($type, \JInstallerAdapterComponent $parent)
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
}
