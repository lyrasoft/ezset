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
 * Ezset Discover View
 *
 * @since 1.0
 */
class EzsetViewDiscoverHtml extends GridView
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
	protected $name = 'discover';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'discover';

	/**
	 * The list name.
	 *
	 * @var  string
	 */
	protected $viewList = 'discover';

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
			'view_name' => 'discover',
			'view_item' => 'discover',
			'view_list' => 'discover',

			// Column which we allow to drag sort
			'order_column'   => 'discover.catid, discover.ordering',

			// Table id
			'order_table_id' => 'discoverList',

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
		foreach ($this['items'] as $item)
		{
			$class = $item->class;

			if (is_callable(array($class, 'getTitle')))
			{
				$item->title = call_user_func(array($class, 'getTitle'));
			}
			else
			{
				$item->title = $item->name;
			}

			$item->alias = $item->name;
		}
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
		JToolbarHelper::custom('discover.install', 'upload', 'upload', 'JTOOLBAR_INSTALL', true);
		JToolbarHelper::custom('discover.refresh', 'refresh', 'refresh', 'COM_EZSET_TOOLBAR_DISCOVER', false);

		return array();
	}
}
