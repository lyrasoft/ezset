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
	 * onAfterInitialise
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
		/** @see \Ezset\Route\Routing::ipBlock */
		$this->call('Route\Routing::ipBlock');

		/** @see \Ezset\Route\Routing::quickRouting */
		$this->call('Route\Routing::quickRouting');

		/** @see \Ezset\System\Command::execute */
		$this->call('System\Command::execute');

		if ($this->params->get('system.development.LanguageOrphan', 0))
		{
			/** @see \Ezset\System\Language::orphan */
			$this->call('System\Language::orphan');
		}
	}

	/**
	 * onAfterDispatch
	 *
	 * @return  void
	 */
	public function onAfterDispatch()
	{
		if ($this->params->get('system.security.AdminProtect'))
		{
			/** @see \Ezset\System\Secure::adminProtect */
			$this->call('System\Secure::AdminProtect');
		}

		if ($gaId = $this->params->get('GoogleAnalytics'))
		{
			$this->call(array('Seo\Document', 'analytics'), $gaId);
		}
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
			$this->call(array('System\\Cache', 'cacheEzsetData'), $this);
		}

		if ($this->params->get('system.cache.CacheControl', 0) && $this->app->isSite())
		{
			$this->call(array('System\\Cache', 'manage'));
		}
	}
}
