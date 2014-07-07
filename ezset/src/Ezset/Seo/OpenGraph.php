<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Seo;

use Windwalker\Helper\UriHelper;

/**
 * Class OpenGraph
 *
 * @since 1.0
 */
class OpenGraph
{
	/**
	 * Property once.
	 *
	 * @var  int
	 */
	protected static $once = 1;

	/**
	 * setOpenGraph
	 *
	 * @param string $context
	 * @param object $article
	 *
	 * @return  void
	 */
	public static function setOpenGraph($context , $article)
	{
		$es = \Ezset::getInstance();
		$input = \JFactory::getApplication()->input;

		$view = $input->get('view');

		if (empty($article->id))
		{
			return;
		}

		if ('article' == $view)
		{
			$images = new \JRegistry($article->images);
			$img    = $images->get('image_fulltext', $images->get('image_intro'));

			if (!$img)
			{
				include_once EZSET_ROOT . '/lib/dom/simple_html_dom.php';

				// If first image = main image, delete this paragraph.
				$html = str_get_html($article->text);
				$imgs = $html->find('img');

				if (!empty($imgs[0]))
				{
					$img = $imgs[0]->src;
				}
			}

			$cat = \JTable::getInstance('category');
			$cat->load($article->catid);
			$cat->params = new \JRegistry($cat->params);

			$catimg = $cat->params->get('image');

			if (isset($img))
			{
				$es->ogImage = $img;
			}
			elseif ($catimg)
			{
				$es->ogImage = UriHelper::pathAddHost($catimg);

			}
			else
			{
				if (!$es->params->get('ogDefaultImageOnlyFrontPage', 1))
				{
					$es->data->ogImage = UriHelper::pathAddHost($es->params->get('ogDefaultImage'));
				}
			}
		}
		elseif ('category' == $view)
		{
			if (static::$once)
			{
				$cat = \JTable::getInstance('category');
				$cat->load($input->get('id'));
				$cat->params = new \JRegistry($cat->params);

				$img = $cat->params->get('image');

				if ($img)
				{
					$es->ogImage = $img;
				}
				elseif (!$es->params->get('ogDefaultImageOnlyFrontPage', 1))
				{
					$es->ogImage = $es->params->get('ogDefaultImage');
				}

				$es->ogImage = UriHelper::pathAddHost($es->data->ogImage);
			}

			static::$once = 0;
		}
	}
}
