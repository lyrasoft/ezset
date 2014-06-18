<?php
/**
 * @version        $Id: article.php 20196 2011-01-09 02:40:25Z ian $
 * @copyright      Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports a modal article picker.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.6
 */
class JFormFieldCodemirror extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'codemirror';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$editor = \JEditor::getInstance('codemirror');

		$params['linenumbers'] = 1;
		$params['tabmode']     = 'shift';
		$params['width']     = 400;
		$params['height']     = 300;

		$doc = \JFactory::getDocument();
		$doc->addStyleDeclaration(<<<CSS
.CodeMirror
{
	width: 700px;
	height: 400px;
}
CSS
);

		$output = $editor->display($this->name, $this->value, '400px', '400px', 400, 400, false, null, null, null, $params);
		$output = "<fieldset class=\"adminform\"><div style=\"height: 400px; margin-bottom: 30px;\">{$output}</div></fieldset>";

		return $output;
	}
}
