<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

$files = \Ezset\Library\Backup\Backup::fix();

echo '<pre>';
echo implode("<br>", $files);
echo '</pre>';
