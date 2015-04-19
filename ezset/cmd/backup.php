<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

$backupZipFile = new SplFileInfo(JPATH_ROOT . '/tmp/ezset-backup.zip');
$backupSQLFile = new SplFileInfo(JPATH_ROOT . '/tmp/ezset-sql-backup-' . '.sql');
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(JPATH_ROOT));
$app      = JFactory::getApplication();
?>
	<h1>壓縮中，完成將自動下載 - ASIKART Backup System</h1>

	<script>
		setInterval(function()
		{
			var t = document.getElementById('main-textarea');

			t.scrollTop = t.scrollHeight;

			console.log(t.scrollHeight);
		}, 200);
	</script>

	<textarea  cols="120" rows="20" id="main-textarea"><?php

$export = \Ezset\Database\Backup::export();

file_put_contents($backupSQLFile->getPathname(), $export);

//ZIP files
$zip = new ZipArchive;

if (is_file($backupZipFile->getPathname()))
{
	JFile::delete($backupZipFile->getPathname());
}

if ($zip->open($backupZipFile->getPathname(), ZipArchive::CREATE) === true)
{
	foreach ($iterator as $item)
	{
		if ($item->isDir())
		{
			continue;
		}

		echo $item->getPathname() . "\n";

		$zip->addFile($item->getPathname(), str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', $item->getPathname()));
	}

	$zip->addFile($backupSQLFile->getPathname(), $backupSQLFile->getBasename());

	$zip->close();

	echo 'ZIP ok';
}
else
{
	echo 'failed';
}

?></textarea>

<?php

$app->redirect(JUri::base() . '/tmp/' . $backupZipFile->getBasename());
