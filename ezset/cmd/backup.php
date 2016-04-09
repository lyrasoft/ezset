<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use Ezset\Library\Auth\Authentication;
use Ezset\Library\Backup\Backup;
use Ezset\Library\Backup\DatabaseExporter;
use Ezset\Library\Backup\OutputBuffer;
use Ezset\Library\System\ResponseHelper;

defined('_JEXEC') or die;

ResponseHelper::simpleUTF8Header();

Authentication::authenticate();

set_time_limit(0);
ini_set('memory_limit', '1G');

$backupZipFile   = new SplFileInfo(Backup::getBackupZipFile());
$backupSQLFile   = new SplFileInfo(Backup::getBackupSQLFile());
$installFolder   = Backup::getInstallationResource();
$installIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($installFolder));

$ob = new OutputBuffer(JFactory::getApplication()->input);

$ob->start();

Backup::prepareFolder($backupZipFile->getPath());
Backup::writeHtaccess($backupZipFile->getPath() . '/.htaccess');
Backup::writeWebConfig($backupZipFile->getPath() . '/web.config');

?>
<style>
	body {
		background-color: #333;
		color: #eee;
	}
	#main-textarea {
		background-color: #111;
		border-radius: 5px;
		padding: 15px;
	}
</style>
<h1>壓縮中，完成將自動下載 - Ezset Backup System</h1>

<script>
	var stop = false
		,toBottom = function() {
			if (stop)
			{
				return;
			}

			var t = document.getElementById('main-textarea');

			t.scrollTop = t.scrollHeight;
		};

	setInterval(toBottom, 200);
</script>

<pre id="main-textarea" style="height: 500px; overflow: scroll;"><?php

DatabaseExporter::export($backupSQLFile->getPathname());

//ZIP files
$zip = new ZipArchive;

if (is_file($backupZipFile->getPathname()))
{
	JFile::delete($backupZipFile->getPathname());
}

$ob->flush();

if ($zip->open($backupZipFile->getPathname(), ZipArchive::CREATE) === true)
{
	// Prepare all files iterator
	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(JPATH_ROOT, RecursiveDirectoryIterator::SKIP_DOTS));

	/** @var \SplFileInfo $item */
	foreach ($iterator as $item)
	{
		$dest = str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', $item->getPathname());

		// Excludes
		if (Backup::filter($item->getPathname()))
		{
			continue;
		}

		if ($item->isDir())
		{
			$zip->addEmptyDir(Backup::encode($dest));

			continue;
		}

		$ob->out($item->getPathname() . '  =>  ' . $dest . "\n");

		$zip->addFile($item->getPathname(), Backup::encode($dest));
	}

	foreach ($installIterator as $item)
	{
		if ($item->isDir())
		{
			continue;
		}

		$dest = str_replace($installFolder . DIRECTORY_SEPARATOR, '', 'installation' . DIRECTORY_SEPARATOR . $item->getPathname());

		$ob->out($item->getPathname() . '  =>  ' . $dest . "\n");

		$zip->addFile($item->getPathname(), str_replace('\\', '/', $dest));
	}

	$zip->addFile($backupSQLFile->getPathname(), $backupSQLFile->getBasename());

	$zip->deleteName('configuration.dist.php');
	$zip->renameName('configuration.php', 'configuration.dist.php');

	$zip->close();

	echo 'ZIP ok';
}
else
{
	echo 'failed';
}

?></pre>

<script>
	toBottom();
	stop = true;
</script>

<?php

$ob->endClean();

Backup::redirectToDownload();

// UriHelper::download($backupZipFile->getPathname(), true, true);
