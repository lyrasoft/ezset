<?php
/**
 * @version        $Id: article.php 20196 2011-01-09 02:40:25Z ian $
 * @copyright      Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

use Ezset\Library\Script\EzsetScript;
use Windwalker\Dom\HtmlElement;
use Windwalker\Dom\SimpleXml\XmlHelper;

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * The JFormFieldOverride class.
 *
 * @since  __DEPLOY_VERSION__
 */
class JFormFieldAjaxbutton extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'Ajaxbutton';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		if (!JPluginHelper::isEnabled('system', 'ezset'))
		{
			return JText::_('COM_EZSET_MESSAGE_MUST_ENABLE_PLUGIN_FIRST');
		}

		$cmd = XmlHelper::get($this->element, 'cmd');

		$this->prepareScript($cmd);

		$id = str_replace('.', '_', $cmd);
		$lang = strtoupper($id);

		$link = new HtmlElement(
			'div',
			array(
				new HtmlElement('button', JText::_('COM_EZSET_FIELD_AJAXBUTTON_EXECUTE'), array('class' => 'btn btn-default ajax-button', 'type' => 'button', 'id' => $this->id . '_button')),
				new HtmlElement('span', null, array('id' => $this->id . '_response', 'style' => 'margin-left: 15px', 'class' => 'ajax-button-response'))
			),
			array(
				'id' => 'control_' . $this->id,
				'data-cmd' => $cmd
			)
		);

		return $link;
	}

	/**
	 * prepareScript
	 *
	 * @param string $cmd
	 *
	 * @return void
	 */
	protected function prepareScript($cmd)
	{
		EzsetScript::core();
		$token = JFactory::getSession()->getFormToken();

		$ajaxUrl  = JURI::root() . 'index.php?cmd=ajax.' . $cmd;
		$ajaxCode = <<<JS
jQuery(function($) {
	Ezset.ajaxButton('#control_{$this->id}', '$ajaxUrl', '$token');
});
JS;
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($ajaxCode);
	}
}