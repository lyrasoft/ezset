<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

$configDistFile = new SplFileInfo(INSTALL_ROOT . '/../configuration.dist.php');
$configFile = new SplFileInfo(INSTALL_ROOT . '/../configuration.php');

include_once $configDistFile->getPathname();

$config = new Registry;

$config->loadObject(new JConfig);

$options = $this->input->getVar('install');

$options['select'] = false;
$options['driver'] = $config->get('driver');

$db = JDatabaseDriver::getInstance($options);

try
{
	// Create
	$db->setQuery('CREATE DATABASE IF NOT EXISTS ' . $db->qn($options['database']) . ' CHARACTER SET = "utf8"')->execute();

	$db->select($options['database']);

	// Import
	$sql = new SplFileInfo(JPATH_BASE . '/ezset-sql-backup.sql');

	$queries = file_get_contents($sql->getPathname());

	$queries = $db->splitSql($queries);

	foreach ($queries as $query)
	{
		$db->setQuery($query)->execute();
	}

	$config->set('host',     $options['host']);
	$config->set('db',       $options['database']);
	$config->set('password', $options['password']);
	$config->set('user',     $options['user']);
	$config->set('log_path', JPATH_BASE . '/logs');
	$config->set('tmp_path', JPATH_BASE . '/tmp');

	file_put_contents($configFile->getPathname(), $config->toString('php', array('class' => 'JConfig')));

	$this->redirect('index.php?page=complete');
}
catch (\Exception $e)
{
	echo $e;

	exit();
}
