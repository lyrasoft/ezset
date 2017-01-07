<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Listener\Templating;

use Ezset\Listener\AbstractEzsetListener;

/**
 * The TemplatingListener class.
 *
 * @since  __DEPLOY_VERSION__
 */
class TemplatingListener extends AbstractEzsetListener
{
	/**
	 * onAfterDispatch
	 *
	 * @return  void
	 */
	public function onAfterDispatch()
	{
		/** @see \Ezset\Asset\Asset::registerScripts */
		$this->call('Asset\Asset::registerScripts');
	}

	/**
	 * onAfterRender
	 *
	 * @return  void
	 */
	public function onAfterRender()
	{
		/** @see \Ezset\Asset\Asset::registerStyles */
		$this->call(array('Asset\Asset', 'registerStyles'));
	}

	/**
	 * onBeforeCompileHead
	 *
	 * @return  void
	 */
	public function onBeforeCompileHead()
	{
		/** @see \Ezset\Asset\Asset::favicon */
		$this->call(array('Asset\Asset', 'favicon'));
	}
}
