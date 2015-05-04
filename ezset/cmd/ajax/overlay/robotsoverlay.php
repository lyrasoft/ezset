<?php

defined('_JEXEC') or die;

header('Content-Type: text/html; charset=utf-8');

$dest = new SplFileInfo(JPATH_ROOT . '/robots.txt');
$src = new SplFileInfo(JPATH_ROOT . '/plugins/system/ezset/resources/seo/robots.txt');

if (is_file($dest->getPathname()))
{
	JFile::delete($dest->getPathname());
}

if (JFile::copy($src->getPathname(), $dest->getPathname()))
{
	echo '覆蓋完成';
}
else
{
	echo '覆蓋失敗，請檢查 configuration.php 權限';
}
