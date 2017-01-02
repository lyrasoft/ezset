<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

use Ezset\Library\Addon\AddonHelper;

/**
 * Ezset Component
 *
 * @since 1.0
 */
final class EzsetComponent extends \Ezset\Library\Component\EzsetComponent
{
	/**
	 * Default task name.
	 *
	 * @var string
	 */
	protected $defaultController = 'config.display';

	/**
	 * Prepare hook of this component.
	 *
	 * Do some customize initialise through extending this method.
	 *
	 * @return void
	 */
	public function prepare()
	{
		parent::prepare();

		AddonHelper::getAddons();
	}
}
