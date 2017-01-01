<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

use Ezset\Addon\AddonHelper;
use Windwalker\String\StringInflector;

// No direct access
defined('_JEXEC') or die;

/**
 * Ezset helper.
 *
 * @since 1.0
 */
abstract class EzsetHelper
{
	/**
	 * Configure the Link bar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 */
	public static function addSubmenu($vName)
	{
		$app = \JFactory::getApplication();

		$view = $app->input->get('view', 'addons');

		JHtmlSidebar::addEntry(
			JText::_('COM_EZSET_VIEW_ADDONS'),
			'index.php?option=com_ezset&view=addons',
			$view === 'addon'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_EZSET_VIEW_DISCOVER'),
			'index.php?option=com_ezset&view=discover',
			$view === 'discover'
		);

//		$addons = AddonHelper::findAddons();
//
//		$addonName = $app->input->get('addon', 'system');
//
//		foreach ($addons as $addon => $instance)
//		{
//			JHtmlSidebar::addEntry(
//				JText::_(sprintf('COM_EZSET_ADDON_%s_TITLE', strtoupper($addon))),
//				'index.php?option=com_ezset&view=config&addon=' . $addon,
//				$addonName === $addon
//			);
//		}

		$dispatcher = \JEventDispatcher::getInstance();
		$dispatcher->trigger('onAfterAddSubmenu', array('com_ezset', $vName));
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   string  $option  Action option.
	 *
	 * @return  JObject
	 */
	public static function getActions($option = 'com_ezset')
	{
		$user   = JFactory::getUser();
		$result = new \JObject;

		$actions = array(
			'core.admin',
			'core.manage',
			'core.create',
			'core.edit',
			'core.edit.own',
			'core.edit.state',
			'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $option));
		}

		return $result;
	}
}
