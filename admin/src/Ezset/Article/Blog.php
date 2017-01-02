<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use Ezset\Library\Layout\FileLayout;
use Joomla\String\StringHelper;
use PHPHtmlParser\Dom;
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
		$html      = new Dom;
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
		if ($es->params->get('blogViewClearly', 0))
		{
			// If first image = main image, delete this paragraph.
			$html = $html->load($text);

			/** @var \PHPHtmlParser\Dom\Collection|\PHPHtmlParser\Dom\HtmlNode[] $imgs */
			$imgs = $html->find('img');

			if ($imgs->count())
			{
				$mainImg = $imgs[0]->src;

				// Is img in p tag?
				/** @var \PHPHtmlParser\Dom\HtmlNode $p */
				$p = $imgs[0]->getParent();

				// If image has anchor, get parent.
				if ($p->getTag() != 'p')
				{
					$p = $p->getParent();
				}

				// remove first img
				$p->setInnerHtml(str_replace($p->firstChild()->outerHtml(), '', $p->innerHtml()));

				if (!trim($p->innerHtml()))
				{
					$p->setOuterHtml('');
				}

				$text = (string) $html;
			}

			if ($es->params->get('blogViewCleanTags', 0))
			{
				$text = strip_tags($text, $allowTags);

				if (!$allowTags)
				{
					$text = StringHelper::substr($text, 0, $maxChar);
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
