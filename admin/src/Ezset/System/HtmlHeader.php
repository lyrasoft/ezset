<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\System;

/**
 * The HtmlHeader class.
 *
 * @since  __DEPLOY_VERSION__
 */
class HtmlHeader
{
	/**
	 * setGenerator
	 *
	 * @return  void
	 */
	public static function setGenerator()
	{
		if (!\Ezset::hasHtmlHeader())
		{
			return;
		}

		$params = \Ezset::getParams();
		$doc = \JFactory::getDocument();

		// Set Generator
		if ($generator = $params->get('system.basic.Generator'))
		{
			$doc->setGenerator($generator);
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
		if (!\Ezset::hasHtmlHeader())
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
