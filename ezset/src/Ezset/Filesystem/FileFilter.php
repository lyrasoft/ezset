<?php
/**
 * Part of joomla341 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Filesystem;

/**
 * The FileFilter class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class FileFilter
{
	/**
	 * Property rules.
	 *
	 * @var  array
	 */
	protected $rules = array();

	/**
	 * Property root.
	 *
	 * @var  string
	 */
	protected $root = JPATH_ROOT;

	/**
	 * Class init.
	 *
	 * @param array  $rules
	 * @param string $root
	 */
	public function __construct($rules, $root = JPATH_ROOT)
	{
		$this->rules = $rules;

		foreach ($this->rules as &$rule)
		{
			$rule = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $rule);
		}

		$this->root = $root;
	}

	/**
	 * test
	 *
	 * @param string $string
	 *
	 * @return  boolean
	 */
	public function test($string)
	{
		$match = false;

		$string = substr($string, strlen(rtrim($this->root, DIRECTORY_SEPARATOR)));

		foreach ($this->rules as $rule)
		{
			// Negative
			if (substr($rule, 0, 1) == '!')
			{
				$rule = substr($rule, 1);

				if (fnmatch($rule, $string))
				{
					$match = false;
				}
			}
			// Normal
			else
			{
				if (fnmatch($rule, $string))
				{
					$match = true;
				}
			}
		}

		return $match;
	}

	/**
	 * Method to get property Rules
	 *
	 * @return  array
	 */
	public function getRules()
	{
		return $this->rules;
	}

	/**
	 * Method to set property rules
	 *
	 * @param   array $rules
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setRules($rules)
	{
		$this->rules = $rules;

		return $this;
	}

	/**
	 * Method to get property Root
	 *
	 * @return  string
	 */
	public function getRoot()
	{
		return $this->root;
	}

	/**
	 * Method to set property root
	 *
	 * @param   string $root
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setRoot($root)
	{
		$this->root = $root;

		return $this;
	}
}
