<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

$session = JFactory::getSession();
$session->checkToken() or die;

$input = JFactory::getApplication()->input;

$content = $input->getString('content');
$client = $input->get('client', 'site');

$file = $client == 'site' ? 'custom.css' : 'custom-admin.css';
$file = new SplFileInfo(EZSET_FRONT . '/css/' . $file);

if (!is_file($file->getPath()))
{
	JFolder::create($file->getPath());
}

file_put_contents($file->getPathname(), $content);

$response = new \Windwalker\View\Json\JsonBuffer(array('file' => $file->getPathname()), JText::_('PLG_SYSTEM_EZSET_SAVE_SUCCESS'));

header('Content-Type: text/json');

echo $response;
