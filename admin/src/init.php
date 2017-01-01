<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

$init = JPATH_LIBRARIES . '/windwalker/src/init.php';

if (!is_file($init))
{
	JFactory::getApplication()->enqueueMessage('Windwalker Framework not found.', 'error');

	return false;
}

include_once JPATH_LIBRARIES . '/windwalker/src/init.php';

if (is_dir(JPATH_BASE . '/components/com_ezset'))
{
	JLoader::registerPrefix('Ezset', JPATH_BASE . '/components/com_ezset');
	JLoader::register('EzsetComponent', JPATH_BASE . '/components/com_ezset/component.php');
}

JLoader::registerNamespace('Ezset', JPATH_ADMINISTRATOR . '/components/com_ezset/src');
JLoader::registerNamespace('Windwalker', __DIR__);

return true;
