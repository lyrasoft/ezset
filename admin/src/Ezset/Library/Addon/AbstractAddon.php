<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Library\Addon;

use Windwalker\Event\ListenerHelper;

/**
 * The AbstractAddon class.
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class AbstractAddon
{
	/**
	 * Property isEnabled.
	 *
	 * @var  boolean
	 */
	public static $isEnabled = true;

	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected static $name;

	/**
	 * Property element.
	 *
	 * @var  array
	 */
	protected $data;

	/**
	 * AbstractAddon constructor.
	 *
	 * @param array $data
	 *
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 * @throws \UnexpectedValueException
	 */
	public function __construct($data = array())
	{
		$this->data = $data ? : $this->loadData();
	}

	/**
	 * register
	 *
	 * @return  static
	 * @throws \RuntimeException
	 */
	public function register()
	{
		// Load fields
		\JForm::addFieldPath(static::getDir('field'));
		\JForm::addRulePath(static::getDir('rule'));

		$this->registerClasses();
		$this->registerListeners();
	}

	/**
	 * registerClasses
	 *
	 * @return  void
	 * @throws \RuntimeException
	 */
	protected function registerClasses()
	{
		\JLoader::registerNamespace('Ezset', static::getDir('src'));
	}

	/**
	 * registerListeners
	 *
	 * @return  void
	 */
	protected function registerListeners()
	{
		$path = static::getDir('src/Ezset/Listener');

		if (is_dir($path))
		{
			// Register ezset event listeners
			ListenerHelper::registerListeners(
				'Ezset',
				\JEventDispatcher::getInstance(),
				$path
			);
		}

	}

	/**
	 * getDir
	 *
	 * @param string $suffix
	 *
	 * @return string
	 */
	public static function getDir($suffix = '')
	{
		$ref = new \ReflectionClass(get_called_class());

		$suffix = $suffix ? '/' . ltrim($suffix, '/') : $suffix;

		return dirname($ref->getFileName()) . $suffix;
	}

	/**
	 * isEnabled
	 *
	 * @return  boolean
	 */
	public static function isEnabled()
	{
		return static::$isEnabled;
	}

	/**
	 * getName
	 *
	 * @return  string
	 */
	public static function getName()
	{
		if (static::$name === null)
		{
			static::$name = strtolower(str_replace('EzsetAddon', '', get_called_class()));
		}

		return static::$name;
	}

	/**
	 * getTitle
	 *
	 * @return  string
	 */
	public static function getTitle()
	{
		return \JText::_(sprintf('COM_EZSET_ADDON_%s_TITLE', strtoupper(static::getName())));
	}

	/**
	 * getMenuItem
	 *
	 * @param string $current
	 *
	 * @return  void
	 */
	public function addMenuItem($current = '')
	{
		\JHtmlSidebar::addEntry(
			\JText::_(sprintf('COM_EZSET_ADDON_%s_TITLE', strtoupper(static::getName()))),
			'index.php?option=com_ezset&view=config&addon=' . static::getName(),
			static::getName() === $current
		);
	}

	/**
	 * loadData
	 *
	 * @return  array
	 * @throws \UnexpectedValueException
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 */
	protected function loadData()
	{
		$name = static::getName();

		$addon = \JTable::getInstance('Addon', 'EzsetTable');

		if (!$addon->load(array('name' => $name)))
		{
			throw new \UnexpectedValueException(sprintf('Addon: %s not found.', $name));
		}

		return $addon->getProperties();
	}

	/**
	 * Method to get property Data
	 *
	 * @return  array
	 */
	public function getData()
	{
		return $this->data;
	}
}