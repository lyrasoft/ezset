<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();

header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

\Ezset\System\AuthoriseHelper::auth();

$uri = JUri::getInstance();

$backupZipFile = new SplFileInfo(JPATH_ROOT . '/tmp/ezset/backup/ezset-backup-' . $uri->getHost() . '.zip');
$backupSQLFile = new SplFileInfo(JPATH_ROOT . '/tmp/ezset/backup/ezset-sql-backup.sql');
$iterator      = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(JPATH_ROOT));

$installationFolder   = realpath(__DIR__ . '/../resources/installation');
$installationIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($installationFolder));

$quite = $app->input->get('quite', 0);

if ($quite)
{
	ob_start();
}

// Create folder
if (!JFolder::exists($backupZipFile->getPath()))
{
	JFolder::create($backupZipFile->getPath());
}

if (!JFile::exists($backupZipFile->getPath() . '/.htaccess'))
{
	$htaccess = <<<HT
<IfModule !mod_authz_core.c>
Order deny,allow
Deny from all
</IfModule>
<IfModule mod_authz_core.c>
  <RequireAll>
    Require all denied
  </RequireAll>
</IfModule>
HT;

	file_put_contents($backupZipFile->getPath() . '/.htaccess', $htaccess);
}

?><h1>壓縮中，完成將自動下載 - ASIKART Backup System</h1>

	<script>
		var stop = false
			,toBottom = function()
			{
				if (stop)
				{
					return;
				}

				t = document.getElementById('main-textarea')

				t.scrollTop = t.scrollHeight;
			};

		setInterval(toBottom, 200);
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

ob_flush();
flush();

if ($zip->open($backupZipFile->getPathname(), ZipArchive::CREATE) === true)
{
	$ignores = array(
		'/logs/*',
		'!/logs/index.html',
		'/log/*',
		'!/log/index.html',
		'/cache/*',
		'!/cache/index.html',
		'/tmp/*',
		'!/tmp/index.html',
		'/administrator/components/com_akeeba/backup/*.zip',
	);

	$filter = new \Ezset\Filesystem\FileFilter($ignores);

	foreach ($iterator as $item)
	{
		if ($item->isDir())
		{
			continue;
		}

		$dest = str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', $item->getPathname());

		// Excludes
		if ($filter->test($item->getPathname()))
		{
			continue;
		}

		echo $item->getPathname() . '  =>  ' . $dest . "\n";
		ob_flush();
		flush();

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
		ob_flush();
		flush();

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
		toBottom();
		stop = true;
	</script>

<?php

if ($quite)
{
	ob_end_clean();
}

// $app->redirect(JUri::base() . 'tmp/' . $backupZipFile->getBasename());
$app->redirect(JUri::base() . '?cmd=backup.download');
