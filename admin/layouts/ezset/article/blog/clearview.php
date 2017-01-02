<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/** @var $displayData array */
$link     = $displayData['link'];
$imageUrl = $displayData['image_url'];
$article  = $displayData['article'];
$imgWidth = $displayData['image_width'];
$text     = $displayData['text'];
?>
<div class="ak_blog_layout">
	<div class="ak_blog_img_wrap fltlft float-left">
		<a class="ak_blog_img_link" href="<?php echo $link; ?>">
			<img class="ak_blog_img" src="<?php echo $imageUrl; ?>" alt="<?php echo $article->title; ?>"
				width="<?php echo $imgWidth; ?>px" />
		</a>
	</div>
	<div class="ak_blog_intro">
		<?php echo $text; ?>
	</div>
	<div class="clr clearfix"></div>
</div>
