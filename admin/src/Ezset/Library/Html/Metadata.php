<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Library\Html;

/**
 * The HtmlHeader class.
 *
 * @since  __DEPLOY_VERSION__
 */
class Metadata
{
	/**
	 * Property data.
	 *
	 * @var  array
	 */
	protected static $data = array(
		'meta' => array(
			'description' => '',
			'keywords' => ''
		),
		'og' => array()
	);

	/**
	 * setMataDescription
	 *
	 * @param   string $content
	 *
	 * @return  void
	 */
	public static function setMataDescription($content)
	{
		static::$data['meta']['description'] = $content;
	}

	/**
	 * getMetaDescription
	 *
	 * @return  string
	 */
	public static function getMetaDescription()
	{
		return static::getMetadata('description');
	}

	/**
	 * setMetaKeywords
	 *
	 * @param   string  $keywords
	 *
	 * @return  void
	 */
	public static function setMetaKeywords($keywords)
	{
		static::$data['meta']['keywords'] = $keywords;
	}

	/**
	 * getMetaKeywords
	 *
	 * @return  string
	 */
	public static function getMetaKeywords()
	{
		return static::getMetadata('keywords');
	}

	/**
	 * addOpengraph
	 *
	 * @param string       $name
	 * @param string|array $value
	 * @param bool         $replace
	 *
	 * @return  void
	 */
	public static function addOpengraph($name, $value, $replace = false)
	{
		if (!isset(static::$data['og'][$name]) || $replace)
		{
			static::$data['og'][$name] = array();
		}

		if (is_array($value))
		{
			foreach ($value as $v)
			{
				static::addOpengraph($name, $v);
			}

			return;
		}

		static::$data['og'][$name][] = $value;
	}

	/**
	 * getMetadata
	 *
	 * @param string $name
	 *
	 * @return array|string
	 */
	public static function getMetadata($name = null)
	{
		if ($name === null)
		{
			return static::$data['meta'];
		}

		if (isset(static::$data['meta'][$name]))
		{
			return static::$data['meta'][$name];
		}

		return false;
	}

	/**
	 * getOpengraph
	 *
	 * @param string $name
	 * @param bool   $first
	 *
	 * @return array|mixed
	 */
	public static function getOpengraph($name = null, $first = false)
	{
		if ($name === null)
		{
			return static::$data['og'];
		}

		if (isset(static::$data['og'][$name]))
		{
			if ($first && static::$data['og'][$name][0])
			{
				return static::$data['og'][$name][0];
			}

			return static::$data['og'][$name];
		}

		return array();
	}

	/**
	 * Method to get property Data
	 *
	 * @return  array
	 */
	public static function getData()
	{
		return static::$data;
	}
}
