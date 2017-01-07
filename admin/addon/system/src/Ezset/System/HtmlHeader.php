<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\System;

use Ezset\Core\CodeLoader;

/**
 * The HtmlHeader class.
 *
 * @since  __DEPLOY_VERSION__
 */
class HtmlHeader
{
	/**
	 * insertHeader
	 *
	 * @return  void
	 */
	public static function insertHeader()
	{
		$ezset = \Ezset::getInstance();

		$header = $ezset->params->get('system.basic.InsertHeader', '');
		$header = trim($header);

		if (!\Ezset::hasHtmlHeader() || !$header)
		{
			return;
		}

		$body = $ezset->app->getBody();

		$body = explode('</head>', $body);

		$body[0] .= "\n" . CodeLoader::import(md5($ezset->app->get('secret') . 'header'), $header) . "\n";
		$body = implode('</head>', $body);
		
		$ezset->app->setBody($body);
	}

	/**
	 * setGenerator
	 *
	 * @return  void
	 */
	public static function setGenerator()
	{
		$generator = \Ezset::getInstance()->params->get('system.basic.Generator');

		if (!$generator || !\Ezset::hasHtmlHeader())
		{
			return;
		}

		$doc = \JFactory::getDocument();

		// Set Generator
		$doc->setGenerator($generator);
	}

	/**
	 * analytics
	 *
	 * @return  void
	 */
	public static function analytics()
	{
		$id = \Ezset::getInstance()->params->get('system.basic.GoogleAnalytics');

		if (!$id || !\Ezset::hasHtmlHeader())
		{
			return;
		}

		$doc = \JFactory::getDocument();

		$host = \JUri::getInstance()->getHost();

		$script = <<<GA

  /* Google Analytics Start by Ezset */
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
