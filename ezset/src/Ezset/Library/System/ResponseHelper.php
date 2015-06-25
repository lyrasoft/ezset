<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Library\System;

/**
 * The ResponseHelper class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class ResponseHelper
{
	/**
	 * Make simple HTML page with UTF-8 header and do not cache.
	 *
	 * Useful on Ajax.
	 *
	 * @return  void
	 */
	public static function simpleUTF8Header()
	{
		header('Content-Type: text/html; charset=utf-8');
		header('Cache-Control: no-cache');
	}
}
