<?php
/**
 * Part of joomla341 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\System;

/**
 * The AuthoriseHelper class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class AuthoriseHelper
{
	/**
	 * auth
	 *
	 * @param bool $superUser
	 *
	 * @throws \Exception
	 */
	public static function auth($superUser = true)
	{
		$app = \JFactory::getApplication();

		try
		{
			$username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
			$password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

			$user = \JUser::getInstance($username);

			if (!$username || $user->username != $username)
			{
				throw new \Exception;
			}

			if (!$password || !\JUserHelper::verifyPassword($password, $user->password))
			{
				throw new \Exception;
			}

			if ($superUser)
			{
				if (!$user->authorise('core.admin'))
				{
					throw new \Exception;
				}
			}
		}
		catch (\Exception $e)
		{
			header('WWW-Authenticate: Basic realm="' . $app->get('sitename') . '"');
			header('HTTP/1.0 401 Unauthorized');

			exit();
		}
	}
}
