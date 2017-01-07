<?php
/**
 * Part of joomla341 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use Ezset\Library\System\ResponseHelper;

defined('_JEXEC') or die;

\Ezset\Library\Auth\Authentication::authenticate();

$backupZipFile = new SplFileInfo(\Ezset\Library\Backup\Backup::getBackupZipFile());

ResponseHelper::download($backupZipFile->getPathname(), true, true);
