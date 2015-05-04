<?php
/**
 * Part of joomla341 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

\Ezset\System\AuthoriseHelper::auth();

$uri = JUri::getInstance();

$backupZipFile = new SplFileInfo(JPATH_ROOT . '/tmp/ezset/backup/ezset-backup-' . $uri->getHost() . '.zip');

\Windwalker\Helper\UriHelper::download($backupZipFile->getPathname(), true, true);
