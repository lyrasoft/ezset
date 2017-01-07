<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

use Ezset\Library\System\ResponseHelper;
use Ezset\Config\ConfigOverride;
use Windwalker\Api\Buffer\JsonBuffer;

defined('_JEXEC') or die;

ResponseHelper::ajaxResponse(function (JsonBuffer $json)
{
	ConfigOverride::overrideExtension('com_content', array (
		'show_category' => '0',
		'link_category' => '0',
		'show_parent_category' => '0',
		'show_author' => '0',
		'show_publish_date' => '0',
		'show_item_navigation' => '1',
		'show_readmore_title' => '0',
		'show_tags' => '0',
		'show_icons' => '0',
		'show_print_icon' => '0',
		'show_email_icon' => '0',
		'show_hits' => '0',
		'urls_position' => '1',

		'show_urls_images_frontend' => '1',
		'show_urls_images_backend' => '1',
		'targeta' => 1,
		'targetb' => 1,
		'targetc' => 1,
		'float_intro' => 'none',
		'float_fulltext' => 'none',

		'category_layout' => '_:blog',
		'maxLevel' => '0',
		'show_no_articles' => '0',
		'show_subcat_desc' => '0',
		'show_cat_tags' => '0',

		'show_cat_num_articles_cat' => '0',

		'num_leading_articles' => '0',
		'num_intro_articles' => '10',
		'num_columns' => '1',
		'num_links' => '0',
		'multi_column_order' => '0',
		'show_subcategory_content' => '-1',

		'show_pagination_limit' => '0',
		'list_show_hits' => '0',
		'list_show_author' => '0',

		'orderby_pri' => 'none',
		'orderby_sec' => 'rdate',

		'feed_show_readmore' => '1',
	));

	$json->message = \JText::_('COM_EZSET_ARTICLE_LAYOUT_OVERRIDE_MESSASGE_SUCCESS');
});
