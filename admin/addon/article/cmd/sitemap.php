<?php

use Asika\Sitemap\Sitemap;
use Ezset\Library\Article\ArticleHelper;
use Windwalker\Helper\DateHelper;
use Windwalker\Helper\UriHelper;

defined('_JEXEC') or die;

// Get some datas
$app       = JFactory::getApplication();
$doc       = JFactory::getDocument();
$linkCache = array();
$date      = DateHelper::getDate('now');

// Routing for prepare some required info for languageFilter plugin
$uri = clone JUri::getInstance();

$router = $app::getRouter();
$result = $router->parse($uri);

// Locale
$locale = null;

if (JLanguageMultilang::isEnabled())
{
	$locale = JFactory::getLanguage()->getTag();
}

// Get XML parser
$sitemap = new Sitemap;

$xml = simplexml_load_string(
	'<?xml version="1.0" encoding="utf-8"?' . '>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" />'
);

// Set frontpage
$sitemap->addItem(JUri::root(), '0.9', 'daily', $date);

// Build menu map
$db    = JFactory::getDbo();
$query = $db->getQuery(true);

$query->select("*")
	->from("#__menu")
	->where("id != 1")
	->where("published=1")
	->where("access=1");

if ($locale)
{
	$query->where($query->format('language IN (%q, %q)', $locale, '*'));
}

$db->setQuery($query);
$menus = $db->loadObjectList();

foreach ($menus as $menu)
{
	if (!$menu->link)
	{
		continue;
	}

	if (in_array($menu->type, array('alias', 'url')))
	{
		continue;
	}

	// Fix URI bugs
	$uri = new JUri($menu->link);
	$uri->setVar('Itemid', $menu->id);

	if ($app->get('sef'))
	{
		$uri->setVar('layout', null);
	}

	$link = JRoute::_($uri->toString());
	$host = str_replace('http://' . $_SERVER['HTTP_HOST'], '', JUri::root());
	$link = str_replace($host, '', $link);
	$link = UriHelper::pathAddHost($link);

	// Set xml data
	$sitemap->addItem($link, '0.8', 'weekly', $date);

	$linkCache[] = $link;
}

// Build category map
$query = $db->getQuery(true);

$query->select("*")
	->from("#__categories")
	->where("id != 1")
	->where("published = 1")
	->where("access = 1")
	->where("extension = 'com_content'");

if ($locale)
{
	$query->where($query->format('language IN (%q, %q)', $locale, '*'));
}

$db->setQuery($query);
$cats = $db->loadObjectList();

foreach ($cats as $cat)
{
	// Get category link
	$link = ArticleHelper::getCategoryLink($cat->id);

	if (in_array($link, $linkCache))
	{
		continue;
	}

	// Set some data
	$modified = ($cat->modified_time != '0000-00-00 00:00:00') ? $cat->modified_time : $cat->created_time;
	$modified = JFactory::getDate($modified, JFactory::getConfig()->get('offset'));
	$modified = $modified->format('Y-m-d');

	// Set xml data
	$sitemap->addItem($link, '0.7', 'weekly', $modified);

	$linkCache[] = $link;
}

// Build content map
$where = \Windwalker\Model\Helper\QueryHelper::publishingItems('', 'state');

$query = $db->getQuery(true);

$query->select("*")
	->from("#__content")
	->where($where)
	->order('id DESC');

if ($locale)
{
	$query->where($query->format('language IN (%q, %q)', $locale, '*'));
}

$db->setQuery($query);
$contents = $db->loadObjectList();

foreach ($contents as $content)
{
	// Get category link
	$link = ArticleHelper::getArticleLink($content->id, $content->catid);

	if (in_array($link, $linkCache))
	{
		continue;
	}

	// Set some data
	$modified = ($content->modified != '0000-00-00 00:00:00') ? $content->modified : $content->created;
	$modified = JFactory::getDate($modified, JFactory::getConfig()->get('offset'));

	// Set xml data
	$sitemap->addItem($link, '0.6', 'weekly', $modified);

	$linkCache[] = $link;
}

// Output
header("Content-type: text/xml");

echo $sitemap;
