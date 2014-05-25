<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

$wwInit = JPATH_LIBRARIES . '/windwalker/src/init.php';

if (is_file($wwInit))
{
	include_once $wwInit;
}
else
{
	throw new \RuntimeException('Please install Windwalker framework 2.x first to enable Ezset');
}

define('EZSET_FRONT', JPATH_ROOT . '/ezset');
define('EZSET_ROOT', realpath(__DIR__ . '/..'));

if (is_dir(EZSET_FRONT . '/src'))
{
	\JLoader::registerNamespace('MyEzset', EZSET_FRONT . '/src');
}

\JLoader::registerNamespace('Ezset', EZSET_ROOT . '/src');

include_once __DIR__ . '/bc.php';
