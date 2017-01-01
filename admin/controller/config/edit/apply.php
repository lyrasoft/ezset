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
class EzsetControllerConfigEditApply extends \Windwalker\Controller\Edit\ApplyController
{
	/**
	 * Method to run this controller.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		return $this->fetch($this->prefix, $this->name . '.edit.save');
	}
}
