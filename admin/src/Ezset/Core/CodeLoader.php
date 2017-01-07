<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Core;

/**
 * The CodeLoader class.
 *
 * @since  __DEPLOY_VERSION__
 */
class CodeLoader
{
	/**
	 * import
	 *
	 * @param string $filename
	 * @param string $content
	 *
	 * @return  string
	 */
	public static function import($filename, $content)
	{
		// Add access check
		$content = "<?" . "php defined('_JEXEC') or die('restricted access'); ?>\n" . $content;

		$fileName    = JPATH_ROOT . '/tmp/inputcode/code/' . $filename . '.php';
		$fileHash    = md5($content);

		if (!file_exists($fileName))
		{
			\JFile::write($fileName, $content);
		}

		$tmpName    = $fileName;
		$tmpContent = file_get_contents($tmpName);
		$tmpHash    = md5($tmpContent);

		if ($tmpHash !== $fileHash)
		{
			\JFile::write($tmpName, $content);
		}

		ob_start();

		include $tmpName;

		// Fixed joomla bug
		$output = str_replace('$', '\$', ob_get_contents());

		ob_end_clean();

		return $output;
	}
}
