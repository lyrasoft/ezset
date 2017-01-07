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
	ConfigOverride::overrideGlobal(array(
		'log_path' => JPATH_ROOT . '/logs',
		'tmp_path' => JPATH_ROOT . '/tmp',
	));

	$json->message = \JText::_('COM_EZSET_SYSTEM_CONFIG_OVERRIDE_MESSASGE_SUCCESS');
});
