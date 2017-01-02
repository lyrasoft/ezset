<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

$initFile = JPATH_ADMINISTRATOR . '/components/com_ezset/src/init.php';

if (is_file($initFile))
{
	return include_once $initFile;
}
else
{
	return false;
}