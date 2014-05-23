<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

include_once JPATH_LIBRARIES . '/windwalker/src/init.php';

define('EZSET_FRONT', JPATH_ROOT . '/ezset');
define('EZSET_ROOT', realpath(__DIR__ . '/..'));

\JLoader::registerNamespace('MyEzset', EZSET_FRONT . '/src');
\JLoader::registerNamespace('Ezset', EZSET_ROOT . '/src');

include_once __DIR__ . '/bc.php';

// if (JDEBUG)
{
	\Windwalker\Debugger\Debugger::registerWhoops();
}
