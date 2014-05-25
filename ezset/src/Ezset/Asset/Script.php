<?php
/**
 * Part of joomla330 project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Asset;

/**
 * Class Script
 *
 * @since 1.0
 */
class Script
{
	/**
	 * register
	 *
	 * @return  void
	 */
	public static function register()
	{
		/** @var $doc \JDocumentHtml */
		$doc  = \JFactory::getDocument();
		$es   = \Ezset::getInstance();

		if ($doc->getType() !== 'html')
		{
			return;
		}

		$doc->addScript(\JUri::root(true) . '/plugins/system/ezset/asset/js/ezset.js');
		$doc->addScript(\JUri::root(true) . '/ezset/js/ezset-custom.js');
	}
}
