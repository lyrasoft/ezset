<?php
/**
 * Part of Ezset project.
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
	return false;
}

define('EZSET_FRONT', JPATH_ROOT . '/ezset');
define('EZSET_ROOT', realpath(__DIR__ . '/..'));

include_once EZSET_ROOT . '/lib/autoload.php';

if (is_dir(EZSET_FRONT . '/src'))
{
	\JLoader::registerNamespace('MyEzset', EZSET_FRONT . '/src');
}

\JLoader::registerNamespace('Ezset', EZSET_ROOT . '/src');

// Include frontend composer
if (is_file(EZSET_FRONT . '/vendor/autoload.php'))
{
	include_once EZSET_FRONT . '/vendor/autoload.php';
}

// Register ezset event listeners
Windwalker\Event\ListenerHelper::registerListeners(
    'MyEzset',
    JEventDispatcher::getInstance(),
    EZSET_FRONT . '/src/MyEzset/Listener'
);

// Include custom init.php
if (is_file(EZSET_FRONT . '/src/init.php'))
{
	include_once EZSET_FRONT . '/src/init.php';
}

return true;
