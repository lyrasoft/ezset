<?php
/**
 * Part of joomla330 project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Seo;

use Ezset\Helper\UriHelper;

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

		if (\Ezset::isHome())
		{
			$ezset->data->metaDesc = $config->get('MetaDesc');
		}
		else
		{
			$metaDesc = $article->text;
			$metaDesc = strip_tags($metaDesc);
			$metaDesc = trim($metaDesc);
			$metaDesc = \JString::substr($metaDesc, 0, $ezset->params->get('maxMetaChar', 250));

			$ezset->data->metaDesc = $metaDesc;
		}

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
		$easyset  = \Ezset::getInstance();
		$doc      = \JFactory::getDocument();
		$config   = \JFactory::getConfig();
		$siteName = $config->get('sitename');
		$view     = $input->get('view');
		$title    = $doc->getTitle();

		// Fix for YOOTheme
		$title = explode('|', $title);
		$title = $title[0];

		$easyset->data->originTitle = $title;

		if (UriHelper::isHome())
		{
			$easyset->data->siteTitle = $config->get('sitename');
		}
		else
		{
			$separator = trim($easyset->params->get('titleSeparator'));

			$replace['{%SITE%}']  = $siteName;
			$replace['{%TITLE%}'] = $title;

			if ('category' == $view || 'categories' == $view)
			{
				$replace['{%CATEGORY%}'] = '';
			}
			else
			{
				$replace['{%CATEGORY%}'] = $easyset->data->catName;
			}

			$siteTitle = strtr($easyset->params->get('titleFix'), $replace);
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

			$easyset->data->siteTitle = $siteTitle;
		}
	}
}
