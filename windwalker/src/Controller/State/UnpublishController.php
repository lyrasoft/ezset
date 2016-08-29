<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Controller\State;

/**
 * Unpublish Controller
 *
 * @since 2.0
 */
class UnpublishController extends AbstractUpdateStateController
{
	/**
	 * The data fields to update.
	 *
	 * @var string
	 */
	protected $stateData = array(
		'state' => '0'
	);

	/**
	 * Action text for translate.
	 *
	 * @var string
	 */
	protected $actionText = 'UNPUBLISHED';

	/**
	 * Are we allow return?
	 *
	 * @var  boolean
	 */
	protected $allowReturn = true;
}
