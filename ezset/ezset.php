<?php
/**
 * @package        Asikart.Plugin
 * @subpackage     system.plg_ezset
 * @copyright      Copyright (C) 2014 Asikart.com, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\Data\Data;
use Windwalker\Registry\Registry;

// No direct access
defined('_JEXEC') or die;

$result = include_once __DIR__ . '/src/init.php';

if ($result):

/**
 * Ezset System Plugin
 *
 * @package        Joomla.Plugin
 * @subpackage     System.ezset
 * @since          1.0
 */
class PlgSystemEzset extends JPlugin
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
	 * Constructor
	 *
	 * @param  object  $subject The object to observe
	 * @param  array   $config  An array that holds the plugin configuration
	 */
	public function __construct($subject, $config)
	{
		$this->ezset = Ezset::getInstance();

		parent::__construct($subject, $config);

		$this->params = $this->ezset->params;

		$this->loadLanguage();
	}

	/**
	 * onAfterInitialise
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
		/** @see \Ezset\System\Command::execute */
		$this->call('Core\Command::execute');
	}

	/**
	 * onBeforeCompileHead
	 *
	 * @return  void
	 */
	public function onBeforeCompileHead()
	{
		/** @see \Ezset\Core\HtmlHeader::prepareHtmlHeader */
		$this->call('Core\HtmlHeader::prepareHtmlHeader', $this);
	}

	/**
	 * resultBool
	 *
	 * @param bool[] $results
	 *
	 * @return  bool
	 */
	public function assertAllTrue(array $results = array())
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
	public function call($callable)
	{
		return call_user_func_array(array($this->ezset, 'call'), func_get_args());
	}
}

endif;
