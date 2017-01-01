<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

use Windwalker\Bootstrap\Dropdown;

// No direct access
defined('_JEXEC') or die;

/**
 * Prepare data for this template.
 *
 * @var $data \Windwalker\Data\Data
 * @var $grid \Windwalker\View\Helper\GridHelper
 * @var $item \Windwalker\Data\Data
 */
$grid = $data->grid;
$item = $grid->current;

// Duplicate
Dropdown::duplicate($grid->row, 'addons.batch');

Dropdown::divider();

// Published & Unpublished
if ($item->state)
{
	Dropdown::unpublish($grid->row, 'addons.state');
}
else
{
	Dropdown::publish($grid->row, 'addons.state');
}

// Trash & Delete
if (JDEBUG || $data->state->get('filter.addon.state') == -2)
{
	Dropdown::addCustomItem(\JText::_('JTOOLBAR_DELETE'), 'delete', $grid->row, 'addons.state.delete');
}
else
{
	Dropdown::trash($grid->row, 'addons.state');
}

// Render it
echo Dropdown::render();
