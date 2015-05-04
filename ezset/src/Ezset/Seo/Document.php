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
 * Class Document
 *
 * @since 1.0
 */
class Document
{
	/**
	 * register
	 *
	 * @return  void
	 */
	public static function register()
	{
		$easyset = \Ezset::getInstance();
		$doc = \JFactory::getDocument();

		if ($easyset->app->isAdmin() || $doc->getType() != 'html')
		{
			return;
		}

		/** @var $params \JRegistry */
		$params   = $easyset->params;
		$config   = \JFactory::getConfig();
		$siteName = $config->get('sitename');
		$metaDesc = '';

		if ($params->get('getMeta'))
		{
			$metaDesc = \Ezset::isHome() ? $config->get('MetaDesc') : $easyset->data->metaDesc;

			$doc->setDescription($metaDesc);
		}

		// SEO Title
		$easyset->call(array('Seo\\ContentSeo', 'setTitle'));

		if ($params->get('titleFix') && $easyset->data->siteTitle)
		{
			$doc->setTitle($easyset->data->siteTitle);
		}

		// Set Generator
		if ($generator = $params->get('generator'))
		{
			$doc->setGenerator($generator);
		}

		// Set Open Graph
		if ($params->get('openGraph', 1))
		{
			$meta = array();

			// Og:image
			if (\Ezset::isHome())
			{
				if ($params->get('ogDefaultImage'))
				{
					$meta[] = '<meta property="og:image" content="' . UriHelper::pathAddHost($params->get('ogDefaultImage')) . '"/>';
				}
			}
			elseif ($easyset->data->ogImage)
			{
				$meta[] = '<meta property="og:image" content="' . UriHelper::pathAddHost($easyset->data->ogImage) . '"/>';
			}

			// Others
			$url      = $doc->getBase() ? : \JUri::base();
			$admin_id = $params->get('ogAdminId');
			$page_id  = $params->get('ogPageId');
			$app_id   = $params->get('ogAppId');

			$title = $params->get('ogOnlyTitle', 1) ? $easyset->data->originTitle : $doc->getTitle();

			$meta[] = '<meta property="og:title" content="' . $title . '"/>';
			$meta[] = '<meta property="og:site_name" content="' . $siteName . '"/>';

			if ($metaDesc)
			{
				$meta[] = '<meta property="og:description" content="' . $metaDesc . '"/>';
			}

			$meta[] = '<meta property="og:url" content="' . $url . '"/>';

			// Admin, page, user ids
			if ($admin_id)
			{
				$meta[] = '<meta property="fb:admins" content="' . $admin_id . '"/>';
			}

			if ($page_id)
			{
				$meta[] = '<meta property="fb:page_id" content="' . $page_id . '"/>';
			}

			if ($app_id)
			{
				$meta[] = '<meta property="fb:app_id" content="' . $app_id . '"/>';
			}

			foreach ($meta as $v)
			{
				/** @var $doc \JDocumentHtml */
				$doc->addCustomTag($v);
			}
		}
	}

	/**
	 * analytics
	 *
	 * @param   string $id
	 *
	 * @return  void
	 */
	public static function analytics($id)
	{
		$doc = \JFactory::getDocument();

		$host = \JUri::getInstance()->getHost();

		$script = <<<GA

  /* Google Analytics Start */
  ;
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '{$id}', '{$host}');
  ga('send', 'pageview');
  /* Google Analytics End */


GA;

		$doc->addScriptDeclaration($script);
	}
}
