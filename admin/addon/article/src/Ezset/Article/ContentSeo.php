<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use Ezset\Library\Html\Metadata;
use Stringy\Stringy;

/**
 * Class ContentSeo
 *
 * @since 1.0
 */
class ContentSeo
{
	/**
	 * Property firstArticle.
	 *
	 * @var  bool
	 */
	protected static $firstArticle = true;

	/**
	 * setMeta
	 *
	 * @param object $article
	 *
	 * @return  void
	 * @throws \Exception
	 * @throws \UnexpectedValueException
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 */
	public static function setMeta($article)
	{
		if (!static::$firstArticle)
		{
			return;
		}

		$ezset  = \Ezset::getInstance();

		$metaDesc = '';

		// Get menu meta, if nonexists, use article meta
		if( isset($article->params, $article->metadesc) && $article->params instanceof \JRegistry)
		{
			$metaDesc = $article->params->get('menu-meta_description' , $article->metadesc);
		}

		if (\Ezset::isHome())
		{
			$metaDesc = $ezset->app->get('MetaDesc');
		}
		elseif (!$metaDesc)
		{
			// Get meta from article content
			$metaDesc = $article->text;

			// Remove script tags
			$metaDesc = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $metaDesc);
			$metaDesc = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $metaDesc);
			$metaDesc = strip_tags($metaDesc);

			$metaDesc = Stringy::create($metaDesc)
				// Remove plugin code like:{rsform 1}
				->regexReplace('\{.*\}', '')
				->collapseWhitespace()
				->safeTruncate($ezset->params->get('article.seo.SeoMeta_AutoGetMeta_Length', 250), '...');

			// Find category name
			if (property_exists($article, 'catid'))
			{
				$category = \JTable::getInstance('Category');
				$category->load($article->catid);

				$ezset->data->set('article.category_name', $category->title);
			}
		}

		Metadata::setMataDescription((string) $metaDesc);

		static::$firstArticle = false;
	}

	/**
	 * setTitle
	 *
	 * @return  void
	 */
	public static function setTitle()
	{
		$ezset    = \Ezset::getInstance();
		$siteName = $ezset->app->get('sitename');
		$view     = $ezset->input->get('view');
		$title    = $ezset->document->getTitle();

		// Fix for YOOTheme
		$title = explode('|', $title);
		$title = $title[0];

		$ezset->data->set('seo.origin_title', $title);

		if (\Ezset::isHome())
		{
			$ezset->data->set('seo.site_title', $ezset->app->get('sitename'));
		}
		else
		{
			$separator = trim($ezset->params->get('article.seo.SeoMeta_TitleFix_Separator'));

			$replace['{%SITE%}']  = $siteName;
			$replace['{%TITLE%}'] = $title;

			if ('category' === $view || 'categories' === $view)
			{
				$replace['{%CATEGORY%}'] = '';
			}
			else
			{
				$replace['{%CATEGORY%}'] = $ezset->data['article.category_name'];
			}

			$siteTitle = strtr($ezset->params->get('article.seo.SeoMeta_TitleFix'), $replace);
			$siteTitle = explode('|', $siteTitle);

			foreach ($siteTitle as $k => $v)
			{
				if (!trim($v))
				{
					unset($siteTitle[$k]);

					continue;
				}

				$siteTitle[$k] = trim($siteTitle[$k]);
			}

			$siteTitle = implode(" {$separator} ", $siteTitle);
			$siteTitle = (string) Stringy::create($siteTitle)->collapseWhitespace();

			$ezset->data->set('seo.site_title', $siteTitle);
		}
	}
}
