<?php
/**
 * Part of joomla341 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

\Ezset\Library\Auth\HttpAuthentication::authenticate();

$backupZipFile = new SplFileInfo(\Ezset\Library\Backup\Backup::getBackupZipFile());

\Windwalker\Helper\UriHelper::download($backupZipFile->getPathname(), true, true);
