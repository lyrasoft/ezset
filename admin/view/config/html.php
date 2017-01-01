<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

use Windwalker\DI\Container;
use Windwalker\Model\Model;
use Windwalker\View\Engine\PhpEngine;
use Windwalker\View\Html\EditView;
use Windwalker\Xul\XulEngine;

// No direct access
defined('_JEXEC') or die;

/**
 * Ezset Configs view
 *
 * @since 1.0
 */
class EzsetViewConfigHtml extends EditView
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
	protected $name = 'config';

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
		/** @var EzsetModelConfig */
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$input = $this->getContainer()->get('input');

			$input->set('hidemainmenu', false);

			$this->addSubmenu();

			$this['sidebar'] = \JHtmlSidebar::render();
		}
	}

	/**
	 * Add the submenu.
	 *
	 * @return  void
	 */
	protected function addSubmenu()
	{
		$helper = ucfirst($this->prefix) . 'Helper';

		if (is_callable(array($helper, 'addSubmenu')))
		{
			$helper::addSubmenu($this->getName());
		}
	}
}
