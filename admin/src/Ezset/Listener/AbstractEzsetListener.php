<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Listener;

use JApplicationCms;
use Windwalker\Registry\Registry;

/**
 * The AbstractListener class.
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class AbstractEzsetListener extends \JEvent
{
	/**
	 * Property app.
	 *
	 * @var  JApplicationCms
	 */
	protected $app;

	/**
	 * Property ezset.
	 *
	 * @var  \Ezset
	 */
	protected $ezset;

	/**
	 * Property params.
	 *
	 * @var  Registry
	 */
	public $params;

	/**
	 * AbstractListener constructor.
	 *
	 * @param object $subject
	 *
	 * @throws \Exception
	 */
	public function __construct(&$subject)
	{
		parent::__construct($subject);

		$this->app    = \JFactory::getApplication();
		$this->ezset  = \Ezset::getInstance();
		$this->params = $this->ezset->params;

		$this->init();
	}

	/**
	 * init
	 *
	 * @return  void
	 */
	protected function init()
	{
		// Override it.
	}

	/**
	 * resultBool
	 *
	 * @param bool[] $results
	 *
	 * @return bool
	 */
	protected function assertAllTrue(array $results = array())
	{
		if (in_array(false, $results))
		{
			return false;
		}

		return true;
	}

	/**
	 * call
	 *
	 * @param   callable  $callable
	 *
	 * @return  mixed
	 */
	protected function call($callable)
	{
		return call_user_func_array(array($this->ezset, 'call'), func_get_args());
	}
}
