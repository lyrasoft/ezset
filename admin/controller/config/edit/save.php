<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

/**
 * The EzsetControllerConfigEditSave class.
 *
 * @since  __DEPLOY_VERSION__
 */
class EzsetControllerConfigEditSave extends \Windwalker\Controller\Edit\SaveController
{
	/**
	 * Property addon.
	 *
	 * @var  string
	 */
	protected $addon;

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->addon = $this->input->get('addon');
	}
}
