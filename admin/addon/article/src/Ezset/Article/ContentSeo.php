<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use Ezset\Library\Html\Metadata;
use Joomla\String\StringHelper;
use Windwalker\Helper\UriHelper;

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
		$app = \JFactory::getApplication();

		if ($app->isAdmin())
		{
			return;
		}

		if (!static::$firstArticle)
		{
			return;
		}

		$config = \JFactory::getConfig();
		$ezset  = \Ezset::getInstance();

		$metaDesc = '';

		// Get menu meta, if nonexists, use article meta
		if( isset($article->params) && $article->params instanceof \JRegistry && isset($article->metadesc))
		{
			$metaDesc = $article->params->get('menu-meta_description' , $article->metadesc);
		}

		if (\Ezset::isHome())
		{
			$metaDesc = $config->get('MetaDesc');
		}
		elseif (!$metaDesc)
		{
			// Get meta from article content
			$metaDesc = $article->text;

			// Remove script tags
			$metaDesc = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $metaDesc);
			$metaDesc = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $metaDesc);

			$metaDesc = strip_tags($metaDesc);

			// Filter plugin like:{rsform 1}
			$metaDesc = preg_replace('/\{.*\}/', '', $metaDesc);

			// Remove line
			$metaDesc = str_replace( "\r\n" , '' , $metaDesc);
			$metaDesc = str_replace( "&nbsp;" , '' , $metaDesc);
			$metaDesc = trim($metaDesc);
			$metaDesc = StringHelper::substr($metaDesc, 0, $ezset->params->get('maxMetaChar', 250));

			// Remove latest word
			$metaDesc = trim($metaDesc);
			$metaDesc = explode(' ' ,$metaDesc);
			$latestWord = array_pop($metaDesc);

			if(strlen($latestWord) > 10)
			{
				$metaDesc[] = $latestWord;
			}

			// Rebuild paragraph
			$metaDesc = implode(' ', $metaDesc);

			// Find category name
			if (property_exists($article, 'catid'))
			{
				$category = \JTable::getInstance('Category');
				$category->load($article->catid);

				$ezset->data->set('article.category_name', $category->title);
			}
		}

		Metadata::setMataDescription($metaDesc);

		static::$firstArticle = false;
	}

	/**
	 * setTitle
	 *
	 * @return  void
	 */
	public static function setTitle()
	{
		$input    = \JFactory::getApplication()->input;
		$ezset    = \Ezset::getInstance();
		$doc      = \JFactory::getDocument();
		$config   = \JFactory::getConfig();
		$siteName = $config->get('sitename');
		$view     = $input->get('view');
		$title    = $doc->getTitle();

		// Fix for YOOTheme
		$title = explode('|', $title);
		$title = $title[0];

		$ezset->data->set('seo.origin_title', $title);

		if (\Ezset::isHome())
		{
			$ezset->data->set('seo.site_title', $config->get('sitename'));
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

			$ezset->data->set('seo.site_title', $siteTitle);
		}
	}
}
