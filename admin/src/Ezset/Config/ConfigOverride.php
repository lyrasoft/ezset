<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Config;

use Windwalker\DataMapper\DataMapper;
use Windwalker\Registry\Registry;
use Windwalker\System\ExtensionHelper;

/**
 * The ConfigOverride class.
 *
 * @since  __DEPLOY_VERSION__
 */
class ConfigOverride
{
	/**
	 * setConfig
	 *
	 * @param array $data
	 *
	 * @return  void
	 */
	public static function overrideGlobal(array $data)
	{
		$config = \JFactory::getConfig();

		$configFile = JPATH_ROOT . '/configuration.php';
		\JPath::setPermissions($configFile, '0644');

		foreach ($data as $key => $value)
		{
			$config->set($key, $value);
		}

		$content = $config->toString('php', array('class' => 'JConfig'));

		\JFile::write($configFile, $content);

		\JPath::setPermissions($configFile, '0444');
	}

	/**
	 * overrideExtension
	 *
	 * @param string $element
	 * @param array  $data
	 *
	 * @return  void
	 * @throws \InvalidArgumentException
	 */
	public static function overrideExtension($element, $data)
	{
		list($type, $name, $group) = array_values(ExtensionHelper::extractElement($element));

		$mapper = new DataMapper('#__extensions', 'extension_id');

		switch ($type)
		{
			case 'component':
				$ext = $mapper->findOne(array('element' => $element));
				break;

			case 'plugin':
				$ext = $mapper->findOne(array('element' => $name, 'folder' => $group));
				break;

			default:
				throw new \InvalidArgumentException('Currently only support component and plugin');
		}

		if ($data !== false)
		{
			$params = new Registry($ext->params);
			$params->load((array) $data);

			$ext->params = $params->toString('json');
		}
		else
		{
			$ext->params = '';
		}

		$mapper->updateOne($ext, 'extension_id');
	}
}
