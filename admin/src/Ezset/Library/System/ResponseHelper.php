<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Library\System;

use Joomla\Utilities\ArrayHelper;

/**
 * The ResponseHelper class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class ResponseHelper
{
	/**
	 * Make simple HTML page with UTF-8 header and do not cache.
	 *
	 * Useful on Ajax.
	 *
	 * @return  void
	 */
	public static function simpleUTF8Header()
	{
		header('Content-Type: text/html; charset=utf-8');
		header('Cache-Control: no-cache');
	}

	/**
	 * A download function to hide real file path. When call this function, will start download instantly.
	 *
	 * This function should call when view has not executed yet, if header sended,
	 *  the file which downloaded will error, because download by stream will
	 *  contain header in this file.
	 *
	 * @param   string  $path     The file system path with filename & type.
	 * @param   boolean $absolute Absolute URL or not.
	 * @param   boolean $stream   Use stream or redirect to download.
	 * @param   array   $option   Some download options.
	 *
	 * @return  void
	 */
	public static function download($path, $absolute = false, $stream = false, $option = array())
	{
		if ($stream)
		{
			if (!$absolute)
			{
				$path = JPATH_ROOT . '/' . $path;
			}

			if (!is_file($path))
			{
				die();
			}

			$file = pathinfo($path);

			$filesize = filesize($path) + ArrayHelper::getValue($option, 'size_offset', 0);
			ini_set('memory_limit', ArrayHelper::getValue($option, 'memory_limit', '1540M'));

			// Set Header
			header('Content-Type: application/octet-stream');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: pre-check=0, post-check=0, max-age=0');
			header('Content-Transfer-Encoding: binary');
			header('Content-Encoding: none');
			header('Content-type: application/force-download');
			header('Content-length: ' . $filesize);
			header('Content-Disposition: attachment; filename="' . $file['basename'] . '"');

			$handle    = fopen($path, 'rb');
			$chunksize = 1 * (1024 * 1024);

			// Start Download File by Stream
			while (!feof($handle))
			{
				$buffer = fread($handle, $chunksize);
				echo $buffer;
				flush();
			}

			fclose($handle);

			jexit();
		}
		else
		{
			if (!$absolute)
			{
				$path = \JUri::root() . $path;
			}

			// Redirect it.
			$app = \JFactory::getApplication();
			$app->redirect($path);
		}
	}
}
