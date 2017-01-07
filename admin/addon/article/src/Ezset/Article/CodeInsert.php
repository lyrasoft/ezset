<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use Ezset\Core\CodeLoader;

/**
 * Class CodeInsert
 *
 * @since 1.0
 */
class CodeInsert
{
	const POSITION_BEFORE_TITLE = 'CustomCode_BeforeTitle';
	const POSITION_AFTER_TITLE = 'CustomCode_AfterTitle';
	const POSITION_BEFORE_CONTENT = 'CustomCode_BeforeContent';
	const POSITION_AFTER_CONTENT = 'CustomCode_AfterContent';

	/**
	 * insertContent
	 *
	 * @param object          $article
	 * @param \PlgSystemEzset $easyset
	 *
	 * @return  void
	 */
	public static function insertContent($article, $easyset)
	{
		// Expression to search for
		$regex = '/{(akcode)\s*(.*?)}/i';

		// Find all instances of $regex (i.e. akcode) in an article and put them in $matches
		$matches = array();
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		foreach ($matches as $dummy)
		{
			$params = array();
			$akcode = "";

			// Get all params
			preg_match_all('/\[.*?\]/', $dummy[2], $params);

			// Remove []
			if ($params)
			{
				foreach ($params as $i => $mm)
				{
					$akcode = preg_replace("/\[|]/", "", $mm);
				}
			}

			// Get first param as file name
			$file = trim(array_shift($akcode));

			$output = '';

			if ($file)
			{
				$base = $easyset->params->get('article.code_insert.CodeInsert_Root', 'ezset/code');
				$base = str_replace('/', '.', $base);
				$base = trim($base, '.');
				$file = "{$base}.{$file}";
				$file = trim($file, '.');

				// Get file content
				ob_start();
				\JLoader::import($file, JPATH_ROOT);

				// Fixed joomla bug
				$output = str_replace('$', '\$', ob_get_contents());
				ob_end_clean();
			}

			$article->text = preg_replace($regex, $output, $article->text, 1);

		}
	}

	/**
	 * customCode
	 *
	 * @param string  $position
	 * @param boolean $prepareContent
	 * @param object  $article
	 *
	 * @return  mixed
	 */
	public static function customCode($position, $prepareContent = false, $article = null)
	{
		$ezset = \Ezset::getInstance();
		$input = \JFactory::getApplication()->input;

		// Generate code output
		$fileContent = $ezset->params->get('article.code_insert.' . $position, '');

		$output = CodeLoader::import(md5($position), $fileContent);

		// Inset the code to article
		if ($prepareContent)
		{
			if (!is_object($article))
			{
				return '';
			}

			switch ($position)
			{
				case static::POSITION_BEFORE_TITLE:
					if ($input->get('view', 'article') === 'article')
					{
						echo $output;
					}
					break;

				case static::POSITION_BEFORE_CONTENT:
					$article->text = $output . $article->text;
					break;
			}

			return '';
		}

		return $output;
	}
}
