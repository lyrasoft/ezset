<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Library\Auth;

/**
 * The WatcherAuthentication class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class WatcherAuthentication
{
	/**
	 * authenticate
	 *
	 * @return  bool
	 *
	 * @throws \Exception
	 */
	public static function authenticate()
	{
		$es = \Ezset::getInstance();
		$input = \JFactory::getApplication()->input;

		$key = $es->params->get('watcherPublicKey');

		if (!$key)
		{
			return false;
		}

		$token = $input->get('access_token');

		if (!$token)
		{
			return false;
		}

		if (static::genPublicKey($token) === $key)
		{
			return true;
		}

		return false;
	}

	/**
	 * genPublicKey
	 *
	 * @param string $token
	 *
	 * @return  string
	 */
	public static function genPublicKey($token)
	{
		return sha1(md5('SimularWatcher' . $token));
	}
}
