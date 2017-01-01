<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

use Joomla\DI\Container;
use Windwalker\Model\Model;
use Windwalker\View\Engine\PhpEngine;
use Windwalker\View\Html\GridView;
use Windwalker\Xul\XulEngine;

// No direct access
defined('_JEXEC') or die;

/**
 * Ezset Configs View
 *
 * @since 1.0
 */
class EzsetViewConfigsHtml extends GridView
{
	/**
	 * The component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'ezset';

	/**
	 * The component option name.
	 *
	 * @var string
	 */
	protected $option = 'com_ezset';

	/**
	 * The text prefix for translate.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_EZSET';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $name = 'configs';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'config';

	/**
	 * The list name.
	 *
	 * @var  string
	 */
	protected $viewList = 'configs';

	/**
	 * Method to instantiate the view.
	 *
	 * @param Model            $model     The model object.
	 * @param Container        $container DI Container.
	 * @param array            $config    View config.
	 * @param SplPriorityQueue $paths     Paths queue.
	 */
	public function __construct(Model $model = null, Container $container = null, $config = array(), \SplPriorityQueue $paths = null)
	{
		$config['grid'] = array(
			// Some basic setting
			'option'    => 'com_ezset',
			'view_name' => 'config',
			'view_item' => 'config',
			'view_list' => 'configs',

			// Column which we allow to drag sort
			'order_column'   => 'config.catid, config.ordering',

			// Table id
			'order_table_id' => 'configList',

			// Ignore user access, allow all.
			'ignore_access'  => false
		);

		// Directly use php engine
		$this->engine = new PhpEngine;

		parent::__construct($model, $container, $config, $paths);
	}

	/**
	 * Prepare data hook.
	 *
	 * @return  void
	 */
	protected function prepareData()
	{
		/** @var EzsetModelConfigs */
	}

	/**
	 * Configure the toolbar button set.
	 *
	 * @param   array   $buttonSet Customize button set.
	 * @param   object  $canDo     Access object.
	 *
	 * @return  array
	 */
	protected function configureToolbar($buttonSet = array(), $canDo = null)
	{
		// Get default button set.
		$buttonSet = parent::configureToolbar($buttonSet, $canDo);

		// In debug mode, we remove trash button but use delete button instead.
		// if (JDEBUG)
		{
			$buttonSet['trash']['access']  = false;
			$buttonSet['delete']['access'] = true;
		}

		return $buttonSet;
	}
}
