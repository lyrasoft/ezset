<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Asset;

use Ezset\Core\HtmlHeader;
use Ezset\Library\Script\EzsetScript;
use Ezset\Library\Uri\UriChecker;
use Windwalker\Dom\HtmlElement;

/**
 * Class Script
 *
 * @since 1.0
 */
class Asset
{
	/**
	 * register
	 *
	 * @return  void
	 */
	public static function registerScripts()
	{
		$ezset = \Ezset::getInstance();

		if (!\Ezset::hasHtmlHeader())
		{
			return;
		}

		$uri = \JUri::getInstance();

		$root = $uri::root();
		$base = $uri::base();
		$host = $uri->toString(array('scheme', 'host')) . '/';

		EzsetScript::core();
	}

	/**
	 * register
	 *
	 * @return  void
	 */
	public static function registerStyles()
	{
		$app = \JFactory::getApplication();
		$doc = \JFactory::getDocument();

		if ($doc->getType() !== 'html')
		{
			return;
		}

		static::appendLink(\JUri::root(true) . '/plugins/system/ezset/asset/css/ezset.css');

		if ($app->isSite())
		{
			static::appendLink(\JUri::root(true) . '/ezset/css/custom-typo.css');
			static::appendLink(\JUri::root(true) . '/ezset/css/custom.css');
		}
		else
		{
			self::appendLink(\JUri::root(true) . '/ezset/css/custom-admin.css');
		}
	}

	/**
	 * appendLink
	 *
	 * @param string $url
	 *
	 * @return  void
	 */
	public static function appendLink($url)
	{
		HtmlHeader::appendHead(new HtmlElement('link', null, array(
			'rel'  => 'stylesheet',
			'href' => $url,
			'type' => 'text/css'
		)));
	}

	/**
	 * favicon
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 */
	public static function favicon()
	{
		$ezset = \Ezset::getInstance();

		if ($ezset->app->isSite())
		{
			$favicon = $ezset->params->get('templating.design.Favicon_Site');
		}
		else
		{
			$favicon = $ezset->params->get('templating.design.Favicon_Admin');
		}

		if (!$favicon)
		{
			return;
		}

		if ($ezset->document->getType() !== 'html')
		{
			return;
		}

		// TODO: A toggle to decide remove exists or not.
		foreach ($ezset->document->_links as $key => $link)
		{
			if ($link['relation'] === 'shortcut icon')
			{
				unset($ezset->document->_links[$key]);

				continue;
			}

			if (substr($key, -4) === '.ico')
			{
				unset($ezset->document->_links[$key]);

				continue;
			}
		}

		$ezset->document->addFavicon(\JUri::root(true) . '/' . $favicon);
	}
}
