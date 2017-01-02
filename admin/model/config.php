<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

use Windwalker\Model\AdminModel;
use Windwalker\Table\TableHelper;

/**
 * Ezset Config model
 *
 * @since 1.0
 */
class EzsetModelConfig extends AdminModel
{
	/**
	 * Component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'ezset';

	/**
	 * The URL option for the component.
	 *
	 * @var  string
	 */
	protected $option = 'com_ezset';

	/**
	 * The prefix to use with messages.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_EZSET';

	/**
	 * The model (base) name
	 *
	 * @var  string
	 */
	protected $name = 'config';

	/**
	 * Item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'config';

	/**
	 * List name.
	 *
	 * @var  string
	 */
	protected $viewList = 'configs';

	/**
	 * getTable
	 *
	 * @param string $name
	 * @param string $prefix
	 * @param array  $options
	 *
	 * @return  JTable
	 */
	public function getTable($name = 'addon', $prefix = '', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if (!$pk)
		{
			$pk = $this['addon'];
		}

		$item = parent::getItem(array('name' => $pk));

		return (object) $item->params;
	}

	/**
	 * save
	 *
	 * @param array $data
	 *
	 * @return  bool
	 * @throws \UnexpectedValueException
	 * @throws \RuntimeException
	 *
	 * @throws \InvalidArgumentException
	 */
	public function save($data)
	{
		$addon = $this->get('addon', 'system');

		if (empty($data['name']))
		{
			$data = array(
				'name' => $addon,
				'params' => $data
			);
		}

		// TODO: Throw exception if not exists after addon manager complete.

		// Init new record
		$table = $this->getTable();

		if (!$table->load(array('name' => $addon)))
		{
			$helper = new TableHelper($table->getTableName());
			$data['id'] = $helper->initRow($addon, array('params' => '{}'));
		}
		else
		{
			$data['id'] = $table->id;
		}

		return parent::save($data);
	}

	/**
	 * getForm
	 *
	 * @param array $data
	 * @param bool  $loadData
	 *
	 * @return  mixed
	 * @throws \Exception
	 * @throws \UnexpectedValueException
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$config = array(
			'control'   => 'jform',
			'load_data' => $loadData
		);
		
		$addon = $this->get('addon', 'system');
		$addonInstance = \Ezset\Library\Addon\AddonHelper::getAddon($addon);

		if (!$addonInstance)
		{
			throw new UnexpectedValueException(sprintf('Addon: %s not exists', $addon));
		}

		$source = $addonInstance::getDir('form/form.xml');

		return $this->loadForm($this->option . '.' . $this->getName() . '.form', $source, $config);
	}

	/**
	 * populateState
	 *
	 * @return  void
	 */
	protected function populateState()
	{
		parent::populateState();

		/** @var JInput $input */
		$input = $this->getContainer()->get('input');

		$addonName = $input->get('addon', 'system');

		$this->set('addon', $addonName);
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A reference to a JTable object.
	 *
	 * @return  void
	 */
	protected function prepareTable(\JTable $table)
	{
		parent::prepareTable($table);
	}

	/**
	 * Post save hook.
	 *
	 * @param JTable $table The table object.
	 *
	 * @return  void
	 */
	public function postSaveHook(\JTable $table)
	{
		parent::postSaveHook($table);
	}

	/**
	 * Method to set new item ordering as first or last.
	 *
	 * @param   JTable $table    Item table to save.
	 * @param   string $position 'first' or other are last.
	 *
	 * @return  void
	 */
	public function setOrderPosition($table, $position = 'last')
	{
		parent::setOrderPosition($table, $position);
	}
}
