<?php
/**
 * Part of joomla341 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Library\Auth;

/**
 * The AuthoriseHelper class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class HttpAuthentication
{
	/**
	 * authenticate
	 *
	 * @param bool $superUser
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function authenticate($superUser = true)
	{
		try
		{
			$username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
			$password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

			$user = \JUser::getInstance($username);

			if (!$username || $user->username != $username)
			{
				throw new AuthException;
			}

			if (!$password || !\JUserHelper::verifyPassword($password, $user->password))
			{
				throw new AuthException;
			}

			if ($superUser)
			{
				if (!$user->authorise('core.admin'))
				{
					throw new AuthException;
				}
			}

			return true;
		}
		catch (AuthException $e)
		{
			header('WWW-Authenticate: Basic realm="Please login first"');
			header('HTTP/1.0 401 Unauthorized');

			exit();
		}
	}
}
