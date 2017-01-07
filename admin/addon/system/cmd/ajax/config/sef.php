<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

use Ezset\Library\System\ResponseHelper;
use Ezset\Config\ConfigOverride;
use Windwalker\Api\Buffer\JsonBuffer;

defined('_JEXEC') or die;

ResponseHelper::ajaxResponse(function (JsonBuffer $json)
{
	\JFile::move(JPATH_ROOT . '/htaccess.txt', JPATH_ROOT . '/.htaccess');

	ConfigOverride::overrideGlobal(array(
		'sef_rewrite' => 1,
		'sef_suffix' => 1
	));

	$json->message = \JText::_('COM_EZSET_SYSTEM_CONFIG_OVERRIDE_MESSASGE_SUCCESS');
});
