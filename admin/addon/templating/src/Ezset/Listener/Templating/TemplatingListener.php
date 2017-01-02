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
		/** @see \Ezset\Asset\Script::register */
		$this->call('Asset\Script::register');
	}

	/**
	 * onAfterRender
	 *
	 * @return  void
	 */
	public function onAfterRender()
	{
		$this->call(array('Asset\\Style', 'register'));
	}

	/**
	 * onBeforeCompileHead
	 *
	 * @return  void
	 */
	public function onBeforeCompileHead()
	{
		$this->call(array('Seo\\Document', 'favicon'));
	}
}
