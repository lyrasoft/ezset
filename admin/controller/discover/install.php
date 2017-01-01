<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

use Ezset\Addon\AddonHelper;

/**
 * The EzsetControllerDiscoverInstall class.
 *
 * @since  __DEPLOY_VERSION__
 */
class EzsetControllerDiscoverInstall extends \Windwalker\Controller\Batch\AbstractBatchController
{
	/**
	 * Property addons.
	 *
	 * @var  stdClass[]
	 */
	protected $addons = array();

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->addons = AddonHelper::findAddons();
	}

	/**
	 * Method to save item.
	 *
	 * @param int   $pk   The primary key value.
	 * @param array $data The item data.
	 *
	 * @return mixed
	 */
	protected function save($pk, $data)
	{
		if (!isset($this->addons[$pk]))
		{
			$this->addMessage(JText::_('COM_EZSET_ADDON_INSTALL_MESSAGE_NOT_FOUND', $pk), 'warning');

			return false;
		}

		$addon = (array) $this->addons[$pk];

		$addon['state'] = 1;
		$addon['manifest'] = '';
		$addon['access'] = 1;

		return $this->getModel()->save($addon);
	}

	/**
	 * getModel
	 *
	 * @param string $name
	 * @param null   $prefix
	 * @param array  $config
	 * @param bool   $forceNew
	 *
	 * @return  object
	 */
	public function getModel($name = 'addon', $prefix = null, $config = array(), $forceNew = false)
	{
		return parent::getModel($name, $prefix, $config, $forceNew);
	}
}
