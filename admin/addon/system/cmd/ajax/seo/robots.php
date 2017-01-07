<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

use Ezset\Library\System\ResponseHelper;
use Windwalker\Api\Buffer\JsonBuffer;

defined('_JEXEC') or die;

ResponseHelper::ajaxResponse(function (JsonBuffer $json)
{
	$dest = new SplFileInfo(JPATH_ROOT . '/robots.txt');
	$src = new SplFileInfo(EZSET_ADMIN . '/resources/seo/robots.txt');

	if (is_file($dest->getPathname()))
	{
		JFile::delete($dest->getPathname());
	}

	if (!JFile::copy($src->getPathname(), $dest->getPathname()))
	{
		throw new \RuntimeException('Override fail');
	}

	$json->message = \JText::_('COM_EZSET_SYSTEM_ROBOTS_OVERRIDE_MESSASGE_SUCCESS');
});
