<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use Ezset\Layout\FileLayout;
use Windwalker\Helper\JContentHelper;

/**
 * Class Facebook
 *
 * @since 1.0
 */
class Facebook
{
	/**
	 * likeButton
	 *
	 * @param string $context
	 * @param object $article
	 *
	 * @return  void
	 */
	public static function likeButton($context, $article)
	{
		$context = explode('.', $context);

		if ($context[0] != 'com_content')
		{
			return;
		}

		$input = \JFactory::getApplication()->input;
		$uri   = \JUri::getInstance();

		// Set Route
		$link = JContentHelper::getArticleLink("{$article->id}:{$article->alias}", $article->catid, false);
		$link = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port')) . \JRoute::_($link);

		// Set like
		$es       = \Ezset::getInstance();
		$position = $es->params->get('fbLikePosition', 1);

		$like = with(new FileLayout('ezset.article.facebook.like'))->render(array('uri' => $link));

		if ($input->get('view') == 'featured' || $input->get('layout') == 'blog')
		{
			if ($es->params->get('fbLikeOnBlog', 0))
			{
				$article->introtext = $like . $article->introtext;
			}
		}
		elseif ($input->get('view') == 'article')
		{
			switch ($position)
			{
				// After Title
				case 1 :
					$article->text = $like . $article->text;
					break;

				// After Content
				case 2 :
					$article->text = $article->text . $like;
					break;

				// Both
				case 3 :
					$article->text = $like . $article->text . $like;
					break;
			}
		}
	}
}
