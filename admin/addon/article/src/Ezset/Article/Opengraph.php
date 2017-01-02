<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use Ezset\Library\Html\Metadata;
use PHPHtmlParser\Dom;
use Windwalker\Helper\UriHelper;
use Windwalker\Registry\Registry;

/**
 * Class OpenGraph
 *
 * @since 1.0
 */
class Opengraph
{
	/**
	 * Property once.
	 *
	 * @var  int
	 */
	protected static $once = 1;

	/**
	 * addGlobalOpengraph
	 *
	 * @return  void
	 */
	public static function prepareGlobalOpengraph()
	{
		$ezset     = \Ezset::getInstance();
		$doc       = \JFactory::getDocument();
		$ogParams  = $ezset->params->extract('article.social');
		$siteName  = $ezset->app->get('sitename');

		$url      = $doc->getBase() ? : \JUri::base();
		$admin_id = $ogParams->get('Opengraph_AdminID');
		$page_id  = $ogParams->get('Opengraph_PageID');
		$app_id   = $ogParams->get('Opengraph_AppID');

		Metadata::addOpengraph('og:title', $siteName, true);
		Metadata::addOpengraph('og:site_name', $siteName, true);
		Metadata::addOpengraph('og:url', $url, true);
		Metadata::addOpengraph('og:image', null, true);

		if (\Ezset::isHome() && $ogParams->get('Opengraph_DefaultImage'))
		{
			Metadata::addOpengraph('og:image', UriHelper::pathAddHost($ogParams->get('Opengraph_DefaultImage')), true);
		}

		// Admin, page, user ids
		if ($admin_id)
		{
			Metadata::addOpengraph('fb:admins', $admin_id, true);
		}

		if ($page_id)
		{
			Metadata::addOpengraph('fb:page_id', $page_id, true);
		}

		if ($app_id)
		{
			Metadata::addOpengraph('fb:app_id', $app_id, true);
		}
	}

	/**
	 * setOpenGraph
	 *
	 * @param string $context
	 * @param object $article
	 *
	 * @return  void
	 */
	public static function overrideOpengraphImages($context , $article)
	{
		$ezset     = \Ezset::getInstance();
		$ogParams  = $ezset->params->extract('article.social');
		$view      = $ezset->input->get('view');

		if (empty($article->id))
		{
			return;
		}

		if (!$ezset->params->get('Opengraph_GetArticleImage', 1))
		{
			return;
		}

		if ('article' === $view)
		{
			$images = new Registry($article->images);
			$ignoreFirst = false;
			$imgs = array();

			// Get article image
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

			$max = $ogParams->get('Opengraph_GetArticleImage_Max', 1);

			foreach ($images as $k => $image)
			{
				if ($ignoreFirst)
				{
					continue;
				}

				if (($k + 1) > $max)
				{
					break;
				}

				$imgs[] = $image->src;
			}

			if (!$imgs && isset($article->catid))
			{
				$cat = \JTable::getInstance('category');
				$cat->load($article->catid);
				$cat->params = new \JRegistry($cat->params);

				if ($cat->params->get('image'))
				{
					$imgs[] = $cat->params->get('image');
				}
			}

			if (!$imgs && !$ezset->params->get('Opengraph_DefaultImage_HomeOnly', 1))
			{
				if ($ezset->params->get('Opengraph_DefaultImage'))
				{
					$imgs[] = $ezset->params->get('Opengraph_DefaultImage');
				}
			}

			$imgs = array_map(array('Windwalker\Helper\UriHelper', 'pathAddHost'), $imgs);

			Metadata::addOpengraph('og:image', $imgs, true);
		}
		elseif ('category' == $view)
		{
			if (static::$once)
			{
				$cat = \JTable::getInstance('category');
				$cat->load($ezset->input->get('id'));
				$cat->params = new Registry($cat->params);

				$img = $cat->params->get('image');
				$image = '';

				if ($img)
				{
					$image = $img;
				}
				elseif (!$ogParams->get('Opengraph_DefaultImage_HomeOnly', 1))
				{
					$image = $ogParams->get('Opengraph_DefaultImage');
				}

				$image = UriHelper::pathAddHost($image);

				Metadata::addOpengraph('og:image', $image, true);
			}

			static::$once = 0;
		}
	}

	/**
	 * overrideOpengraphMetadata
	 *
	 * @return  void
	 */
	public static function overrideOpengraphMetadata()
	{
		$ezset = \Ezset::getInstance();
		$ogParams = $ezset->params->extract('article.social');

		if (!\Ezset::isHome())
		{
			$title = $ogParams->get('Opengraph_OnlyTitle', 1)
				? $ezset->data['seo.origin_title']
				: $ezset->data['seo.site_title'];

			Metadata::addOpengraph('og:title', $title, true);
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
		$ezset = \Ezset::getInstance();

		if ($ezset->app->isAdmin())
		{
			return;
		}

		$unsupported = false;
		$userAgent = $ezset->input->server->getString('HTTP_USER_AGENT');

		if ($userAgent)
		{
			/* Facebook User Agent
			 * facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)
			 * LinkedIn User Agent
			 * LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta Commons-HttpClient/3.1 +http://www.linkedin.com)
			 */
			$pattern = strtolower('/facebookexternalhit|LinkedInBot/x');

			if (preg_match($pattern, strtolower($userAgent)))
			{
				$unsupported = true;
			}
		}

		if ($ezset->app->get('gzip') && $unsupported)
		{
			$ezset->app->set('gzip', 0);
		}
	}
}
