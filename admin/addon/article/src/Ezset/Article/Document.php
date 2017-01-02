<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use Windwalker\Helper\UriHelper;

/**
 * Class Document
 *
 * @since 1.0
 */
class Document
{
	/**
	 * favicon
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 */
	public static function favicon()
	{
		$app = \JFactory::getApplication();
		$es = \Ezset::getInstance();

		if ($app->isSite())
		{
			$favicon = $es->params->get('faviconSite');
		}
		else
		{
			$favicon = $es->params->get('faviconAdmin');
		}

		if (!$favicon)
		{
			return;
		}

		/** @var \JDocumentHTML $doc */
		$doc = \JFactory::getDocument();

		if ($doc->getType() != 'html')
		{
			return;
		}

		foreach ($doc->_links as $key => $link)
		{
			if ($link['relation'] == 'shortcut icon')
			{
				unset($doc->_links[$key]);

				continue;
			}

			if (substr($key, -4) == '.ico')
			{
				unset($doc->_links[$key]);

				continue;
			}
		}

		$doc->addFavicon(\JUri::root(true) . '/' . $favicon);
	}
}
