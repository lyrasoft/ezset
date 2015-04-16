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
 * Class Blog
 *
 * @since 1.0
 */
class Blog
{
	/**
	 * clearView
	 *
	 * @param string     $context
	 * @param object     $article
	 * @param \JRegistry $params
	 *
	 * @return  void
	 */
	public static function clearView($context, $article, $params = null)
	{
		$input = \JFactory::getApplication()->input;

		if ($input->get('layout') != 'blog' && $input->get('view') != 'featured')
		{
			return;
		}

		$es        = \Ezset::getInstance();
		$imgW      = $es->params->get('blogViewImgWidth', 150);
		$maxChar   = $es->params->get('blogViewMaxChar', 250);
		$default   = $es->params->get('blogViewImgDefault');
		$crop      = (boolean) $es->params->get('blogViewImgCrop', true);
		$allowTags = $es->params->get('blogViewTagsAllow');
		$doc       = \JFactory::getDocument();
		$text      = $article->introtext;
		$mainImg   = null;

		if ($doc->getType() != 'html')
		{
			return;
		}

		$thumb = new \Windwalker\Image\Thumb;

		if ($default)
		{
			$thumb->setDefaultImage($default);
		}

		// Clean Tags
		if ($es->params->get('blogViewCleanTags', 1))
		{
			include_once EZSET_ROOT . '/lib/dom/simple_html_dom.php';

			// If first image = main image, delete this paragraph.
			$html = str_get_html($text);
			$imgs = $html->find('img');

			if ($imgs)
			{
				$mainImg = $imgs[0]->src;

				// Is img in p tag?
				$p = $imgs[0]->parent();

				// If image has anchor, get parent.
				if ($p->tag != 'p')
				{
					$p = $p->parent();
				}

				$imgtext      = $p->children[0]->outertext;
				$p->innertext = str_replace($imgtext, '', $p->innertext);

				if (!trim($p->innertext))
				{
					$p->outertext = '';
				}

				$text = $html->save();
				$text = strip_tags($text, $allowTags);

				if (!$allowTags)
				{
					$text = \JString::substr($text, 0, $maxChar);
				}
			}
		}

		// Handle Image
		if ($crop)
		{
			$imageUrl = $thumb->resize($mainImg, $imgW, $imgW, $crop);
		}
		else
		{
			$imageUrl = $thumb->resize($mainImg, $imgW, 999);
		}

		// Article Link
		$link = JContentHelper::getArticleLink($article->id, $article->catid, 0);

		$data = array(
			'link'        => $link,
			'image_width' => $imgW,
			'image_url'   => $imageUrl,
			'article'     => $article,
			'text'        => $text
		);

		// Set layout
		$layout = with(new FileLayout('ezset.article.blog.clearview'))->render($data);

		$article->introtext = $layout;
	}
}
