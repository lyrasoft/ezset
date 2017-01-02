<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Library\Auth;

/**
 * The Authentication class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Authentication
{
	/**
	 * authenticate
	 *
	 * @param bool $superUser
	 *
	 * @return  boolean
	 */
	public static function authenticate($superUser = true)
	{
		// Check Key
		if (WatcherAuthentication::authenticate())
		{
			return true;
		}

		// HTTP
		return HttpAuthentication::authenticate($superUser);
	}
}
