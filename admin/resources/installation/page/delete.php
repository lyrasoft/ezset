<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

if (!is_file(JPATH_BASE . '/configuration.php'))
{
	$this->redirect('index.php');

	exit();
}

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

JFile::delete(JPATH_BASE . '/ezset-sql-backup.sql');
JFile::delete(JPATH_BASE . '/configuration.dist.php');

JFolder::delete(JPATH_BASE . '/installation');

$root = JUri::base() . '../index.php';

$this->redirect($root);
