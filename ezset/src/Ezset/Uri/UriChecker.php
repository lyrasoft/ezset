<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Uri;

use Windwalker\Helper\ArrayHelper;

/**
 * The UriChecker class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class UriChecker
{
	/**
	 * check
	 *
	 * @param array $queries
	 *
	 * @return  bool
	 *
	 * @throws \Exception
	 */
	public static function check($queries)
	{
		if ($queries instanceof \ArrayAccess)
		{
			$queries = iterator_to_array($queries);
		}

		$input = \JFactory::getApplication()->input;

		$request = $input->getArray(array());

		$result = static::query(array($request), $queries);

		return (bool) count($result);
	}

	/**
	 * isArticleEdit
	 *
	 * @return  boolean
	 */
	public static function isArticleEdit()
	{
		return static::check(array('option' => 'com_content', 'view' => 'article', 'layout' => 'edit'));
	}

	/**
	 * Query a two-dimensional array values to get second level array.
	 *
	 * @param   array    $array    An array to query.
	 * @param   mixed    $queries  Query strings, may contain Comparison Operators: '>', '>=', '<', '<='.
	 *                             Example:
	 *                             array(
	 *                                 'id'         => 6,   // Get all elements where id=6
	 *                                 '>published' => 0    // Get all elements where published>0
	 *                             );
	 * @param   boolean  $strict   Use strict to compare equals.
	 * @param   boolean  $keepKey  Keep origin array keys.
	 *
	 * @return  array  An new two-dimensional array queried.
	 *
	 * @since   2.0
	 */
	public static function query($array, $queries = array(), $strict = false, $keepKey = false)
	{
		$results = array();
		$queries = (array) $queries;

		// Visit Array
		foreach ((array) $array as $k => $v)
		{
			$data = (array) $v;

			/*
			 * Key: is query key
			 * Val: is query value
			 * Data: is array element
			 */
			$boolean = array();

			// Visit Query Rules
			foreach ($queries as $key => $val)
			{
				if (substr($key, -2) == '>=')
				{
					$boolean[] = ArrayHelper::getByPath($data, trim(substr($key, 0, -2))) >= $val;
				}
				elseif (substr($key, -2) == '<=')
				{
					$boolean[] = ArrayHelper::getByPath($data, trim(substr($key, 0, -2))) <= $val;
				}
				elseif (substr($key, -1) == '>')
				{
					$boolean[] = ArrayHelper::getByPath($data, trim(substr($key, 0, -1))) > $val;
				}
				elseif (substr($key, -1) == '<')
				{
					$boolean[] = ArrayHelper::getByPath($data, trim(substr($key, 0, -1))) < $val;
				}
				else
				{
					if ($strict)
					{
						$boolean[] = ArrayHelper::getByPath($data, $key) === $val;
					}
					else
					{
						// Workaround for PHP 5.4 object compare bug, see: https://bugs.php.net/bug.php?id=62976
						$compare1 = is_object(ArrayHelper::getByPath($data, $key)) ? get_object_vars(ArrayHelper::getByPath($data, $key)) : ArrayHelper::getByPath($data, $key);
						$compare2 = is_object($val) ? get_object_vars($val) : $val;

						$boolean[] = $compare1 == $compare2;
					}
				}
			}

			// Set Query results
			if (!in_array(false, $boolean, true))
			{
				if ($keepKey)
				{
					$results[$k] = $v;
				}
				else
				{
					$results[] = $v;
				}
			}
		}

		return $results;
	}
}
