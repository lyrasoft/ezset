<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Asset;

use Ezset\Uri\UriChecker;

/**
 * Class Script
 *
 * @since 1.0
 */
class Script
{
	/**
	 * register
	 *
	 * @return  void
	 */
	public static function register()
	{
		/** @var $doc \JDocumentHtml */
		$doc   = \JFactory::getDocument();
		$es    = \Ezset::getInstance();
		$input = \JFactory::getApplication()->input;

		if ($doc->getType() !== 'html')
		{
			return;
		}

		$uri = \JUri::getInstance();

		$root = $uri::root();
		$base = $uri::base();
		$host = $uri->toString(array('scheme', 'host')) . '/';

		if ($smoothScroll = $es->params->get('smoothScroll', 0))
		{
			\JHtmlBehavior::framework(true);
		}

		$smoothScroll = $smoothScroll ? 'true' : 'false';

		$confirmLeave = ($es->params->get('confirmLeave', 0) && UriChecker::isArticleEdit()) ? 'true' : 'false';

		$logoLink = \JFactory::getApplication()->isAdmin() ? 'true' : 'false';

		$script =<<<SCRIPT
<script type="text/javascript">
	var ezsetOption = {
		smoothScroll: {$smoothScroll},
		confirmLeave: $confirmLeave,
		logoLink: $logoLink
	};

	var ezsetConfig = {
		root : '{$root}',
		base : '{$base}',
		host : '{$host}'
	};

	Ezset.init(ezsetOption, ezsetConfig);
</script>
SCRIPT;

		$doc->addCustomTag($script);

		$doc->addScript(\JUri::root(true) . '/plugins/system/ezset/asset/js/ezset.js');
		$doc->addScript(\JUri::root(true) . '/ezset/js/ezset-custom.js');
	}
}
