<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use Windwalker\Helper\LanguageHelper;

/**
 * Class Translate
 *
 * @since 1.0
 */
class Translate
{
	/**
	 * Property replace.
	 *
	 * @var  array
	 */
	protected static $replace = array(
		'aquot' => '',
		'a39'   => '',
		'--'    => '-'
	);

	/**
	 * translateAlias
	 *
	 * @return  void
	 */
	public static function translateAlias()
	{
		$easyset = \Ezset::getInstance();
		$input   = \JFactory::getApplication()->input;

		// Set translate language
		$SourceLan = $easyset->params->get('originLan');
		$ResultLan = $easyset->params->get('tranLlan', 'en');

		// Get query
		$jform = $input->post->getRaw('jform', array());

		if (!isset($jform) || !isset($jform['alias']))
		{
			return;
		}

		$alias = $jform['alias'];
		$title = $jform['title'];

		$titleTmp = explode('::', $jform['title']);

		if (!empty($titleTmp[1]))
		{
			$title = $titleTmp[0];
			$alias = \JFilterOutput::stringURLSafe($titleTmp[1]);
		}

		if (trim($alias) == '')
		{
			$alias = LanguageHelper::translate($title, $SourceLan, $ResultLan);
			$alias = trim($alias);
			$alias = \JFilterOutput::stringURLSafe($alias);

			$alias   = strtr($alias, static::$replace);
			$alias   = trim($alias, '-');
		}

		$jform['alias'] = $alias;
		$jform['title'] = $title;

		$input = \JFactory::getApplication()->input;

		$input->post->set('jform', $jform, 'method', true);
		$input->request->set('jform', $jform);
	}
}
