<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Library\Backup;

/**
 * The BackupOutputBuffer class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class OutputBuffer
{
	/**
	 * Property quite.
	 *
	 * @var  boolean
	 */
	protected $quite = true;

	/**
	 * Property input.
	 *
	 * @var  \JInput
	 */
	protected $input;

	/**
	 * Class init.
	 *
	 * @param \JInput $input
	 */
	public function __construct(\JInput $input)
	{
		$this->input = $input;
		$this->quite = (bool) $input->get('quite', 0);
	}

	/**
	 * start
	 *
	 * @return  void
	 */
	public function start()
	{
		if ($this->quite)
		{
			ob_start();
		}
	}

	/**
	 * endClean
	 *
	 * @return  void
	 */
	public function endClean()
	{
		if ($this->quite)
		{
			ob_end_clean();
		}
	}

	/**
	 * out
	 *
	 * @param string $text
	 *
	 * @return  void
	 */
	public function out($text)
	{
		if (!$this->quite)
		{
			echo $text;

			$this->flush();
		}
	}

	/**
	 * flush
	 *
	 * @return  void
	 */
	public function flush()
	{
		if (!$this->quite)
		{
			flush();
		}
	}
}
