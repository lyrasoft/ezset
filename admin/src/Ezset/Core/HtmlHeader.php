<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Core;

use Ezset\Library\Html\Metadata;
use Windwalker\Dom\HtmlElement;

/**
 * The HtmlHeader class.
 *
 * @since  __DEPLOY_VERSION__
 */
class HtmlHeader
{
	/**
	 * register
	 *
	 * @return  void
	 * @throws \InvalidArgumentException
	 */
	public static function prepareHtmlHeader()
	{
		$ezset = \Ezset::getInstance();

		if (!\Ezset::hasHtmlHeader())
		{
			return;
		}

		$ezset->call('Core\HtmlHeader::renderMetadata');

		$ezset->call('Core\HtmlHeader::renderTitle');

		$ezset->call('Core\HtmlHeader::renderOpengraph');
	}

	/**
	 * renderMetadata
	 *
	 * @return  void
	 */
	public static function renderMetadata()
	{
		$doc = \JFactory::getDocument();

		$metaDesc = trim(Metadata::getMetaDescription());

		if ($metaDesc)
		{
			$doc->setDescription($metaDesc);
		}
	}

	/**
	 * setTitle
	 *
	 * @return  void
	 */
	public static function renderTitle()
	{
		$ezset = \Ezset::getInstance();
		$doc = \JFactory::getDocument();

		if (!$ezset->data['seo.site_title'])
		{
			$doc->setTitle($ezset->data['seo.site_title']);
		}
	}

	/**
	 * setOpengraph
	 *
	 * @return  void
	 */
	public static function renderOpengraph()
	{
		$doc = \JFactory::getDocument();

		foreach (Metadata::getOpengraph() as $property => $values)
		{
			foreach ($values as $value)
			{
				if (!trim($value))
				{
					continue;
				}

				/** @var $doc \JDocumentHtml */
				$doc->addCustomTag((string) new HtmlElement('meta', null, array(
					'property' => $property,
					'content' => $value
				)));
			}
		}
	}

	/**
	 * addBeforeHeadEnd
	 *
	 * @param  string $content
	 *
	 * @return  void
	 */
	public static function appendHead($content)
	{
		$app = \JFactory::getApplication();
		$body  = $app->getBody();
		$body  = explode('</head>', $body, 2);

		$body[0] .= $content . "\n";

		$body = implode('</head>', $body);

		$app->setBody($body);
	}
}
