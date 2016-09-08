<?php
/**
 * Part of ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Library\Layout;

use JLayoutFile;

/**
 * Class FileLayout
 *
 * @since 1.0
 */
class FileLayout extends \JLayoutFile
{
	/**
	 * Property template.
	 *
	 * @var  \JDocumentHTML
	 */
	protected $tpl;

	/**
	 * Class init.
	 *
	 * @param string     $layoutId
	 * @param \JDocument $template
	 * @param string     $basePath
	 * @param array      $options
	 */
	public function __construct($layoutId, \JDocument $template = null, $basePath = null, $options = null)
	{
		$this->tpl = $template ? : \JFactory::getDocument();

		parent::__construct($layoutId, $basePath, $options);
	}

	/**
	 * Refresh the list of include paths
	 *
	 * @return  void
	 */
	protected function refreshIncludePaths()
	{
		// Reset includePaths
		$this->includePaths = array();

		parent::refreshIncludePaths();

		// (1 - lower priority) Frontend base layouts
		$this->addIncludePath(EZSET_ROOT . '/layouts');
		
		// (2 - lower priority) Frontend base layouts
		$this->addIncludePath(EZSET_FRONT . '/layouts');
	}

	/**
	 * Get the default array of include paths
	 *
	 * @return  array
	 *
	 * @since   3.5
	 */
	public function getDefaultIncludePaths()
	{
		$paths = parent::getDefaultIncludePaths();

		$paths[] = EZSET_ROOT . '/layouts';
		$paths[] = EZSET_FRONT . '/layouts';

		return $paths;
	}
}
