<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

/**
 * Class CodeInsert
 *
 * @since 1.0
 */
class CodeInsert
{
	/**
	 * insertHeader
	 *
	 * @return  void
	 */
	public static function insertHeader()
	{
		$doc = \JFactory::getDocument();
		$app = \JFactory::getApplication();

		if ($doc->getType() !== 'html')
		{
			return;
		}

		$es = \Ezset::getInstance();

		$body = $app->getBody();

		$body = explode('</head>', $body);

		$body[0] .= "\n" . $es->params->get('insertHeader', '') . "\n";
		$body = implode('</head>', $body);
		$app->setBody($body);
	}

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
				$base = $easyset->params->get('inputCodeBase', 'ezset/code');
				$base = str_replace('/', '.', $base);
				$base = trim($base, '.');
				$file = "{$base}.{$file}";
				$file = trim($file, '.');

				// Get file content
				ob_start();
				\JLoader::import($file, JPATH_ROOT, null);

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
		$es    = \Ezset::getInstance();
		$input = \JFactory::getApplication()->input;

		// Generate code output
		$fileContent = $es->params->get($position, '');
		$fileName    = JPATH_ROOT . '/tmp/inputcode/code/' . md5($position);
		$fileHash    = md5($fileContent);

		if (!file_exists($fileName))
		{
			\JFile::write($fileName, $fileContent);
		}

		$tmpName    = $fileName;
		$tmpContent = file_get_contents($tmpName);
		$tmpHash    = md5($tmpContent);

		if ($tmpHash !== $fileHash)
		{
			\JFile::write($tmpName, $fileContent);
		}

		ob_start();

		include $tmpName;

		// Fixed joomla bug
		$output = str_replace('$', '\$', ob_get_contents());

		ob_end_clean();

		// Inset the code to article
		if ($prepareContent)
		{
			if (!is_object($article))
			{
				return '';
			}

			switch ($position)
			{
				case 'insertArticleTop' :
					if ($input->get('view', 'article') == 'article')
					{
						echo $output;
					}
					break;

				case 'insertContentTop' :
					$article->text = $output . $article->text;
					break;

				default:
					return '';
					break;
			}
		}
		else
		{
			return $output;
		}
	}
}
