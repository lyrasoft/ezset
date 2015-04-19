<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\System;

/**
 * Class Secure
 *
 * @since 1.0
 */
class Secure
{
	/**
	 * adminBlock
	 *
	 * @return  void
	 *
	 * @throws \RuntimeException
	 */
	public static function adminBlock()
	{
		$app     = \JFactory::getApplication();
		$user    = \JFactory::getUser();
		$session = \JFactory::getSession();
		$es      = \Ezset::getInstance();
		$mode    = $es->params->get('adminSecure');

		if (! $app->isAdmin()
			|| ! $mode
			|| ! $es->params->get('adminSecureCode')
			|| ! $user->get('guest')
			|| $session->get('aksecure'))
		{
			return;
		}

		$logged = false;

		// Http
		if ($mode == 'auth' || $mode == 'auth_user')
		{
			if (substr(php_sapi_name(), 0, 3) == 'cgi')
			{
				$app->enqueueMessage('Not Apache handler, fallback to default login method.', 'warning');

				return;
			}

			if (!$session->get('tried_login'))
			{
				$_SERVER['PHP_AUTH_USER'] = null;
				$session->set('tried_login', true);
			}

			try
			{
				$username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
				$password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

				// Workaround to simulate system lang input
				$_REQUEST['lang'] = '';

				if ($mode == 'auth_user')
				{
					if (!$app->login(array('username' => $username, 'password' => $password), array('remember' => true)))
					{
						throw new \Exception;
					}

					// $session->set('user', \JFactory::getUser($username));
				}
				else
				{
					if ($password != $es->params->get('adminSecureCode'))
					{
						throw new \Exception;
					}
				}

				$logged = true;
			}
			catch (\Exception $e)
			{
				header('WWW-Authenticate: Basic realm="This operation must login."');
				header('HTTP/1.0 401 Unauthorized');
				die();
			}
		}

		// Compat
		elseif ($mode == 'url')
		{
			$logged = isset($_GET[$es->params->get('adminSecureCode')]);

			if (!$logged)
			{
				$app->redirect(\JURI::root());

				exit();
			}
		}

		if ($logged)
		{
			$session->set('aksecure', true);
			$session->set('tried_login', false);

			$app->redirect(\JUri::getInstance()->toString());

			exit();
		}
	}
}
