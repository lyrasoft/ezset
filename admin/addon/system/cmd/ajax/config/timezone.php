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

$input = JFactory::getApplication()->input;

ResponseHelper::ajaxResponse(function (JsonBuffer $json) use ($input)
{
	ConfigOverride::overrideGlobal(array(
		'offset' => $input->getString('config_Timezone', 'UTC')
	));

	$json->message = \JText::_('COM_EZSET_SYSTEM_OVERRIDE_CONFIG_MESSASGE_SUCCESS');
});
