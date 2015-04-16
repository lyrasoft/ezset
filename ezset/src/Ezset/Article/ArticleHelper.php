<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Article;

use Windwalker\Helper\JContentHelper;

/**
 * The ArticleHelper class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class ArticleHelper
{
	/**
	 * getArticleLink
	 *
	 * @param string $slug
	 * @param string $catslug
	 *
	 * @return  string
	 */
	public static function getArticleLink($slug, $catslug = null)
	{
		$uri = \JUri::getInstance();

		$link = JContentHelper::getArticleLink($slug, $catslug, false);

		return $uri->toString(array('scheme', 'user', 'pass', 'host', 'port')) . \JRoute::_($link);
	}

	/**
	 * getCategoryLink
	 *
	 * @param string $catid
	 *
	 * @return  string
	 */
	public static function getCategoryLink($catid)
	{
		$uri = \JUri::getInstance();

		$link = JContentHelper::getCategoryLink($catid, false);

		return $uri->toString(array('scheme', 'user', 'pass', 'host', 'port')) . \JRoute::_($link);
	}
}
