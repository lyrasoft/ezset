<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Listener\System;

use Ezset\Listener\AbstractEzsetListener;

/**
 * The SystemListener class.
 *
 * @since  __DEPLOY_VERSION__
 */
class SystemListener extends AbstractEzsetListener
{
	/**
	 * init
	 *
	 * @return  void
	 */
	protected function init()
	{
		/** @see \Ezset\System\Routing::ipBlock */
		$this->call('System\Routing::ipBlock');

		$this->call('System\Cache::prepareEzsetData', $this->ezset);
	}

	/**
	 * onAfterInitialise
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
		/** @see \Ezset\System\Routing::quickRouting */
		$this->call('System\Routing::quickRouting');

		/** @see \Ezset\System\Language::orphan */
		$this->call('System\Language::orphan');
	}

	/**
	 * onAfterDispatch
	 *
	 * @return  void
	 */
	public function onAfterDispatch()
	{
		/** @see \Ezset\System\Secure::adminProtect */
		$this->call('System\Secure::adminProtect');

		/** @see \Ezset\System\HtmlHeader::analytics */
		$this->call('System\HtmlHeader::analytics');

		/** @see \Ezset\System\HtmlHeader::setGenerator */
		$this->call('System\HtmlHeader::setGenerator');
	}

	/**
	 * onAfterRender
	 *
	 * @return  void
	 */
	public function onAfterRender()
	{
		if ($this->app->get('caching', 0))
		{
			$this->call(array('System\Cache', 'cacheEzsetData'), $this);
		}

		if ($this->params->get('system.cache.CacheControl', 0) && $this->app->isSite())
		{
			$this->call(array('System\Cache', 'manage'));
		}

		/** @see \Ezset\System\HtmlHeader::insertHeader */
		$this->call('System\HtmlHeader::insertHeader');
	}
}
