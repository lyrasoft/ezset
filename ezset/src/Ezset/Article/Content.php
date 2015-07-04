<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use PHPHtmlParser\Dom;
use Windwalker\Helper\HtmlHelper;

/**
 * Class Content
 *
 * @since 1.0
 */
class Content
{
	/**
	 * tidyRepair
	 *
	 * @param object $article
	 *
	 * @return  void
	 */
	public static function tidyRepair($article)
	{
		$input = \JFactory::getApplication()->input;

		if ('com_content' != $input->get('option'))
		{
			return;
		}

		if (!$article->introtext && !$article->fulltext)
		{
			return;
		}

		$article->introtext = HtmlHelper::repair($article->introtext);
		$article->fulltext  = HtmlHelper::repair($article->fulltext);
	}

	/**
	 * saveFirstImage
	 *
	 * @param string  $context
	 * @param \JTable $article
	 *
	 * @return  void
	 */
	public static function saveFirstImage($context, $article)
	{
		if (!property_exists($article, 'images') && $context != 'com_content.article')
		{
			return;
		}

		$image = new \JRegistry($article->images);

		$dom = new Dom;
		$dom->load($article->introtext . $article->fulltext);

		$imgs = $dom->find('img');
		$imageSrc = null;

		if ($imgs->count())
		{
			$imageSrc = $imgs[0]->src;
		}

		$image->set('image_intro', $imageSrc);

		$article->images = $image->toString();
	}
}
