<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

/**
 * The EzsetControllerDisplay class.
 *
 * @since  __DEPLOY_VERSION__
 */
class EzsetControllerConfigDisplay extends \Windwalker\Controller\DisplayController
{
	/**
	 * Property defaultAddon.
	 *
	 * @var  string
	 */
	protected $defaultAddon = 'system';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$addon = $this->input->get('addon', $this->defaultAddon);

		$this->view->set('addon', $addon);
		$this->getModel()->set('addon', $addon);
	}
}
