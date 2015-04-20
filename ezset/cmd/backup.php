<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

$app = JFactory::getApplication();

header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

\Ezset\System\AuthoriseHelper::auth();

$uri = JUri::getInstance();

$backupZipFile = new SplFileInfo(JPATH_ROOT . '/tmp/ezset-backup-' . $uri->getHost() . '.zip');
$backupSQLFile = new SplFileInfo(JPATH_ROOT . '/tmp/ezset-sql-backup.sql');
$iterator      = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(JPATH_ROOT));

$installationFolder   = realpath(__DIR__ . '/../resources/installation');
$installationIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($installationFolder));

$quite = $app->input->get('quite', 0);

?><h1>壓縮中，完成將自動下載 - ASIKART Backup System</h1>

	<script>
		var stop = false;

		setInterval(function()
		{
			if (stop)
			{
				return;
			}

			var t = document.getElementById('main-textarea');

			t.scrollTop = t.scrollHeight;

			console.log(t.scrollHeight);
		}, 200);
	</script>

	<textarea  style="width: 100%;" rows="20" id="main-textarea">
<?php

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
	$excludes = array('tmp', 'cache', 'logs', 'log');

	foreach ($iterator as $item)
	{
		if ($item->isDir())
		{
			continue;
		}

		$dest = str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', $item->getPathname());

		// Excludes
		$continue = false;

		foreach ($excludes as $exclude)
		{
			if (strpos($dest, $exclude . DIRECTORY_SEPARATOR) === 0 && $dest != $exclude . DIRECTORY_SEPARATOR . 'index.html')
			{
				$continue = true;

				break;
			}
		}

		if ($continue)
		{
			continue;
		}

		echo $item->getPathname() . '  =>  ' . $dest . "\n";

		$zip->addFile($item->getPathname(), $dest);
	}

	foreach ($installationIterator as $item)
	{
		if ($item->isDir())
		{
			continue;
		}

		$dest = str_replace($installationFolder . DIRECTORY_SEPARATOR, '', 'installation/' . $item->getPathname());

		echo $item->getPathname() . '  =>  ' . $dest . "\n";

		$zip->addFile($item->getPathname(), $dest);
	}

	$zip->addFile($backupSQLFile->getPathname(), $backupSQLFile->getBasename());

	$zip->renameName('configuration.php', 'configuration.dist.php');

	$zip->close();

	echo 'ZIP ok';
}
else
{
	echo 'failed';
}

?></textarea>

	<script>
		stop = true;
	</script>

<?php

$app->redirect(JUri::base() . 'tmp/' . $backupZipFile->getBasename());
