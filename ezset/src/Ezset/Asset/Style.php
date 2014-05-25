<?php
/**
 * Part of joomla330 project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Asset;

/**
 * Class Style
 *
 * @since 1.0
 */
class Style
{
	/**
	 * register
	 *
	 * @return  void
	 */
	public static function register()
	{
		$app = \JFactory::getApplication();
		$doc = \JFactory::getDocument();

		if ($doc->getType() != 'html')
		{
			return;
		}

		$body = \JResponse::getBody();

		$body  = explode('</head>', $body);
		$style = "\n";

		if ($app->isSite())
		{
			$style .= '<link rel="stylesheet" href="ezset/css/custom-typo.css" type="text/css" />' . "\n";
			$style .= '<link rel="stylesheet" href="ezset/custom.css" type="text/css" />' . "\n";
		}
		else
		{
			$style .= '<link rel="stylesheet" href="ezset/css/custom-admin.css" type="text/css" />' . "\n";
		}

		$body[0] .= $style;

		$body = implode('</head>', $body);
		\JResponse::setBody($body);
	}
}