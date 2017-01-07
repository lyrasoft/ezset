<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\System;

use Ezset\Library\Exception\HttpAuthException;

/**
 * Class Secure
 *
 * @since 1.0
 */
class Secure
{
	const MODE_URL = 'url';
	const MODE_AUTH = 'auth';
	const MODE_AUTH_USER = 'auth_user';

	/**
	 * adminBlock
	 *
	 * @return  void
	 *
	 * @throws \RuntimeException
	 */
	public static function adminProtect()
	{
		$app     = \JFactory::getApplication();
		$user    = \JFactory::getUser();
		$session = \JFactory::getSession();
		$es      = \Ezset::getInstance();
		$mode    = $es->get('system.security.AdminProtect');
		$code    = $es->get('system.security.AdminProtect_Code');

		if (!$es)
		{
			return;
		}

		if (! $app->isAdmin()
			|| !$mode
			|| !$code
			|| !$user->get('guest')
			|| $session->get('ezset.admin_secure'))
		{
			return;
		}

		$logged = false;

		// Http
		if ($mode === static::MODE_AUTH || $mode === static::MODE_AUTH_USER)
		{
			if (strpos(php_sapi_name(), 'cgi') === 0)
			{
				// Not Apache handler, fallback to default login method.
				$app->enqueueMessage(\JText::_('COM_EZSET_ADMIN_PROTECT_MESSAGE_NO_APACHE_HANDLER'), 'warning');

				return;
			}

			if (!$session->get('ezset.tried_login'))
			{
				$_SERVER['PHP_AUTH_USER'] = null;
				$session->set('ezset.tried_login', true);
			}

			try
			{
				$username = $app->input->server->getUsername('PHP_AUTH_USER');
				$password = $app->input->server->get('PHP_AUTH_PW', null, 'raw');

				// Workaround to simulate system lang input
				$_REQUEST['lang'] = '';

				if ($mode == static::MODE_AUTH_USER)
				{
					if (!$app->login(array('username' => $username, 'password' => $password), array('remember' => true)))
					{
						throw new HttpAuthException('Auth fail.');
					}

					// $session->set('user', \JFactory::getUser($username));
				}
				else
				{
					if ($password !== $code)
					{
						throw new HttpAuthException('Auth fail.');
					}
				}

				$logged = true;
			}
			catch (HttpAuthException $e)
			{
				header('WWW-Authenticate: Basic realm="This operation must login."');
				header('HTTP/1.0 401 Unauthorized');
				exit();
			}
		}

		// Compat
		elseif ($mode === static::MODE_URL)
		{
			$logged = $app->input->get->get($code);

			if ($logged !== null)
			{
				$app->redirect(\JURI::root());

				exit();
			}
		}

		if ($logged)
		{
			$session->set('ezset.admin_secure', true);
			$session->set('ezset.tried_login', false);

			$app->redirect(\JUri::getInstance()->toString());

			exit();
		}
	}
}
