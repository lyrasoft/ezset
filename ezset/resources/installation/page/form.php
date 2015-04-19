<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

?><div class="row">
	<div class="col-md-8 col-md-offset-2">
		<form class="form-horizontal" method="post" name="adminForm" id="adminForm" action="index.php?page=install">

			<fieldset>
				<legend>MySQL</legend>

				<div class="form-group">
					<label for="host-input" class="col-sm-2 control-label">Host</label>
					<div class="col-sm-10">
						<input type="text" name="install[host]" class="form-control" id="host-input" placeholder="Host" value="localhost">
					</div>
				</div>

				<div class="form-group">
					<label for="db-input" class="col-sm-2 control-label">Database</label>
					<div class="col-sm-10">
						<input type="text" name="install[database]" class="form-control" id="db-input" placeholder="Database">
					</div>
				</div>

				<div class="form-group">
					<label for="user-input" class="col-sm-2 control-label">User</label>
					<div class="col-sm-10">
						<input type="text" name="install[user]" class="form-control" id="user-input" placeholder="User">
					</div>
				</div>
				<div class="form-group">
					<label for="pass-input" class="col-sm-2 control-label">Password</label>
					<div class="col-sm-10">
						<input type="password" name="install[password]" class="form-control" id="pass-input" placeholder="Password">
					</div>
				</div>
			</fieldset>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Install</button>
				</div>
			</div>
		</form>
	</div>
</div>
