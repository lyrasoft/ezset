<?php
/**
 * Part of joomla341 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

\Ezset\Library\Authorization\AuthoriseHelper::auth();

$uri = JUri::getInstance();

$backupZipFile = new SplFileInfo(JPATH_ROOT . '/tmp/ezset/backup/ezset-backup-' . $uri->getHost() . '.zip');

\Windwalker\Helper\UriHelper::download($backupZipFile->getPathname(), true, true);
