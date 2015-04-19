<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $this->get('title'); ?></title>
	<meta name="generator" content="Asikart Ezset" />
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<style>
		.main-body {
			margin-top: 75px;
		}
	</style>
</head>
<body>
<div class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.php">
				<?php echo $this->get('title'); ?>
			</a>
		</div>
	</div>
</div>
<div class="container main-body">
	<?php echo $content; ?>
</div>

<div id="copyright">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<hr />
				<footer>
					&copy; Asikart Ezset <?php echo gmdate('Y') ?>
				</footer>
			</div>
		</div>
	</div>
</div>
</body>
</html>
