<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

if (!is_file(JPATH_BASE . '/configuration.php'))
{
	$this->redirect('index.php');

	exit();
}

?>
<div class="row">
	<div class="col-md-8 col-md-offset-2 text-center">
		<h2>Install Complete</h2>

		<br />

		<p>
			Delete <code>installation</code> folder.
		</p>

		<br />

		<a class="btn btn-primary btn-lg" href="index.php?page=delete">Delete installation folder and go to Site</a>
	</div>
</div>
