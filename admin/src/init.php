<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
use Ezset\Library\Addon\AddonHelper;

defined('_JEXEC') or die;

$init = JPATH_LIBRARIES . '/windwalker/src/init.php';

if (!is_file($init))
{
	JFactory::getApplication()->enqueueMessage('Windwalker Framework not found.', 'error');

	return false;
}

include_once JPATH_LIBRARIES . '/windwalker/src/init.php';
include_once __DIR__ . '/../vendor/autoload.php';

if (is_dir(JPATH_BASE . '/components/com_ezset'))
{
	JLoader::registerPrefix('Ezset', JPATH_BASE . '/components/com_ezset');
	JLoader::register('EzsetComponent', JPATH_BASE . '/components/com_ezset/component.php');
}

JLoader::register('Ezset', __DIR__ . '/Ezset.php');

JLoader::registerNamespace('Ezset', JPATH_ADMINISTRATOR . '/components/com_ezset/src');
JLoader::registerNamespace('Windwalker', __DIR__);

// Define paths
define('EZSET_ADMIN', dirname(__DIR__));
define('EZSET_RESOURCES', EZSET_ADMIN . '/resources');
define('EZSET_FRONT', \Ezset::getFrontendPath());
define('EZSET_PLUGIN', \Windwalker\Helper\PathHelper::get('plg_system_ezset'));

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

// Init Ezset first
Ezset::getInstance();

foreach (AddonHelper::getAddons() as $addon)
{
	$addon->register();
}

return true;
