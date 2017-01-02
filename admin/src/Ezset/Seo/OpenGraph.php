<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Seo;

use PHPHtmlParser\Dom;
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

		if (!$es->params->get('ogGetInnerPageImage', 1))
		{
			return;
		}

		if ('article' == $view)
		{
			$images = new \JRegistry($article->images);
			$ignoreFirst = false;
			$imgs = array();
			$img  = $images->get('image_fulltext', $images->get('image_intro'));

			if ($img)
			{
				$imgs[] = $img;
			}

			if ($imgs)
			{
				$ignoreFirst = true;
			}

			$dom = new Dom;

			// If first image = main image, delete this paragraph.
			$dom->load($article->text);
			$images = $dom->find('img');

			foreach ($images as $image)
			{
				if ($ignoreFirst)
				{
					continue;
				}

				$imgs[] = $image->src;
			}

			if (!$imgs && isset($article->catid))
			{
				$cat = \JTable::getInstance('category');
				$cat->load($article->catid);
				$cat->params = new \JRegistry($cat->params);

				$imgs[] = $cat->params->get('image');
			}

			if (!$imgs && !$es->params->get('ogDefaultImageOnlyFrontPage', 1))
			{
				$imgs[] = UriHelper::pathAddHost($es->params->get('ogDefaultImage'));
			}

			$es->data->ogImages = $imgs;
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

	/**
	 * Disable Gzip if parsed by Facebook or LinkedIn.
	 *
	 * @return  void
	 *
	 * @see  https://github.com/dgt41/facebookfix/blob/master/facebookfix.php
	 *
	 * @throws \Exception
	 */
	public static function disableGzip()
	{
		$app = \JFactory::getApplication();

		if ($app->isAdmin())
		{
			return;
		}

		$unsupported = false;

		if (isset($_SERVER['HTTP_USER_AGENT']))
		{
			/* Facebook User Agent
			 * facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)
			 * LinkedIn User Agent
			 * LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta Commons-HttpClient/3.1 +http://www.linkedin.com)
			 */
			$pattern = strtolower('/facebookexternalhit|LinkedInBot/x');

			if (preg_match($pattern, strtolower($_SERVER['HTTP_USER_AGENT'])))
			{
				$unsupported = true;
			}
		}

		if (($app->get('gzip') == 1) && $unsupported)
		{
			$app->set('gzip', 0);
		}
	}
}
