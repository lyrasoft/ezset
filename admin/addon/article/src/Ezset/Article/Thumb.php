<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use PHPHtmlParser\Dom;
use Windwalker\Helper\UriHelper;

/**
 * Class Thumb
 *
 * @since 1.0
 */
class Thumb
{
	/**
	 * autoThumb
	 *
	 * @param string     $context
	 * @param object     $article
	 * @param \JRegistry $params
	 *
	 * @return  void
	 */
	public static function autoThumb($context, $article, $params = null)
	{
		\JHtmlBehavior::modal('a.ezset-modal');

		$minimal = 30;

		$es = \Ezset::getInstance();

		$html = new Dom;
		$html->load($article->text);
		$imgs = $html->find('img');

		foreach ($imgs as $img)
		{
			$classes = explode(' ', $img->class);
			$imgUrl  = UriHelper::pathAddHost($img->src);

			// Has class nothumb, skip to next.
			if (in_array('nothumb', $classes))
			{
				continue;
			}

			// If is anchor already, skip to next.
			if (isset($img->parent->tag) && $img->parent->tag == 'a')
			{
				continue;
			}

			// If img tag has no width and height attrs, skip.
			if (!$img->width && !$img->height)
			{
				continue;
			}

			// If not localhost image, skip.
			if (!strpos('-' . $imgUrl, \JURI::root()) && $es->params->get('article.edit.AutoThumbnail_LocalOnly', 1))
			{
				continue;
			}

			// Get img path and size
			$imgPath = \JPath::clean(str_replace(\JURI::root(), JPATH_ROOT . '/', $imgUrl));
			$size    = getimagesize($imgPath);

			// Manul size
			$imgW = $img->width;
			$imgH = $img->height;

			// Original size
			$oriW = $size[0];
			$oriH = $size[1];

			// If too small, skip.
			if ($oriW <= $minimal || $oriH <= $minimal)
			{
				continue;
			}

			// If large ten origin, skip.
			if ($oriW <= $imgW || $oriW <= $imgW)
			{
				continue;
			}

			// Get thumb url
			$thumb    = new \Windwalker\Image\Thumb;
			$img->src = $thumb->resize($imgUrl, $imgW, $imgH);

			$imgtext = $img->outerHtml;
			$imgtext = \JHtml::link($imgUrl, $imgtext, array('class' => 'ezset-modal'));

			$img->setOuterHtml($imgtext);

			$classes = null;
		}

		$article->text = $html->__toString();
	}
}
