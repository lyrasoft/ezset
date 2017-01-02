<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Ezset\Article;

use Joomla\Uri\Uri;
use Windwalker\Helper\CurlHelper;
use Windwalker\Helper\LanguageHelper;
use Windwalker\String\Utf8String;

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
		$ezset = \Ezset::getInstance();
		$input = $ezset->input;
		$params = $ezset->params->extract('article.seo');

		// Set translate language
		$SourceLan = $params->get('AliasTranslate_OriginLang');
		$ResultLan = $params->get('AliasTranslate_TargetLang', 'en');

		// Get query
		$jform = $input->post->getRaw('jform', array());

		if (!isset($jform) || !isset($jform['alias']) || !isset($jform['title']))
		{
			return;
		}

		$alias = $jform['alias'];
		$title = $jform['title'];

//		$titleTmp = explode('::', $jform['title']);
//
//		if (!empty($titleTmp[1]))
//		{
//			$title = $titleTmp[0];
//			$alias = \JFilterOutput::stringURLSafe($titleTmp[1]);
//		}

		if ('' === (string) trim($alias))
		{
			$alias = static::translate($title, $SourceLan, $ResultLan);
			$alias = trim($alias);
			$alias = \JFilterOutput::stringURLSafe($alias);

			$alias   = strtr($alias, static::$replace);
			$alias   = trim($alias, '-');
		}

		$jform['alias'] = $alias;
		$jform['title'] = $title;

		$input->post->set('jform', $jform, 'method', true);
		$input->request->set('jform', $jform);
	}

	/**
	 * Translate a long text by Google, if it too long, will separate it..
	 *
	 * @param   string  $text      String to translate.
	 * @param   string  $SourceLan Translate from this language, eg: 'zh-TW'. Empty will auto detect.
	 * @param   string  $ResultLan Translate to this language, eg: 'en'. Empty will auto detect.
	 * @param   integer $separate  Separate text by a number of words, batch translate them and recombine to return.
	 *
	 * @return  string    Translated text.
	 */
	public static function translate($text, $SourceLan = null, $ResultLan = null, $separate = 0)
	{
		// If text too big, separate it.
		if ($separate)
		{
			if (Utf8String::strlen($text) > $separate)
			{
				$text = Utf8String::str_split($text, $separate);
			}
			else
			{
				$text = array($text);
			}
		}
		else
		{
			$text = array($text);
		}

		$result = '';

		// Do translate by google translate API.
		foreach ($text as $txt)
		{
			$result .= static::gTranslate($txt, $SourceLan, $ResultLan);
		}

		return $result;
	}

	/**
	 * A method to do Google translate.
	 *
	 * @param   string $text      String to translate.
	 * @param   string $SourceLan Translate from this language, eg: 'zh-tw'. Empty will auto detect.
	 * @param   string $ResultLan Translate to this language, eg: 'en'. Empty will auto detect.
	 *
	 * @return  string|bool Translated text.
	 */
	public static function gTranslate($text, $SourceLan, $ResultLan)
	{
		$ezset = \Ezset::getInstance();
		$url = new Uri;

		// For Google APIv2
		$url->setHost('https://www.googleapis.com/');
		$url->setPath('language/translate/v2');

		$key = $ezset->get('article.seo.AliasTranslate_GoogleApiKey');

		$ezset->triggerEvent('onEzsetGetGoogleApi', array(__METHOD__, &$key));

		$query['key']    = $key;
		$query['q']      = urlencode($text);
		$query['source'] = $SourceLan;
		$query['target'] = $ResultLan;

		if (!$text)
		{
			return false;
		}

		$url->setQuery($query);
		$url->toString();
		$response = CurlHelper::get((string) $url);

		if (empty($response->body))
		{
			return '';
		}

		$json = new \JRegistry;
		$json->loadString($response->body, 'json');

		$r = $json->get('data.translations');

		return $r[0]->translatedText;
	}
}
