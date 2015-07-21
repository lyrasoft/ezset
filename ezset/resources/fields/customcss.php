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
class JFormFieldCustomcss extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'customcss';

	/**
	 * Property scriptInited.
	 *
	 * @var  boolean
	 */
	protected static $scriptInited = false;

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$plugin = JPluginHelper::isEnabled('system', 'ezset');

		if (!$plugin)
		{
			return print_r($plugin, 1) . '需要先啟動外掛！';
		}

		$this->loadScript();

		$editor = \JEditor::getInstance('codemirror');
		$client = \Windwalker\Helper\XmlHelper::get($this->element, 'client', 'site');

		$content = $this->getContent($client);

		$params['linenumbers'] = 1;
		$params['tabmode'] = 'shift';
		$params['width']   = 400;
		$params['height']  = 300;
		$params['syntax']  = 'css';

		$doc = \JFactory::getDocument();
		$doc->addStyleDeclaration(<<<CSS
.custom-css-field .CodeMirror
{
	w/idth: 700px;
	height: 400px;
}
CSS
);

		$save = JText::_('PLG_SYSTEM_EZSET_SAVE');

		$output = $editor->display($this->name, $content, '400px', '400px', 400, 400, false, null, null, null, $params);
		$output = <<<HTML
<fieldset class="adminform custom-css-field">
	<div style="margin-bottom: 25px;" class="custom-css-toolbar">
		<button class="btn btn-default" type="button" data-client="{$client}"
			onclick="EzsetCustomCSS.save('#{$this->id}', '{$this->name}', this, event);"><i class="icon-save"></i> {$save}</button>
	</div>
	<div style="height: 400px; margin-bottom: 30px;">{$output}</div>
</fieldset>
HTML;

		return $output;
	}

	/**
	 * getContent
	 *
	 * @param string $client
	 *
	 * @return  string
	 */
	protected function getContent($client = 'site')
	{
		if ($client == 'site')
		{
			$path = EZSET_FRONT . '/css/custom.css';
		}
		else
		{
			$path = EZSET_FRONT . '/css/custom-admin.css';
		}

		if (is_file($path))
		{
			return file_get_contents($path);
		}

		return '';
	}

	/**
	 * loadScript
	 *
	 * @return  void
	 */
	protected function loadScript()
	{
		if (static::$scriptInited)
		{
			return;
		}

		$session = JFactory::getSession();
		$token = $session->getFormToken();

		$doc = JFactory::getDocument();
		$doc->addScript(JUri::root(true) . '/plugins/system/ezset/asset/js/customcss.js');
		$doc->addScriptDeclaration(<<<SC
;EzsetCustomCSS.init('{$token}');
SC
);

		static::$scriptInited = true;

		return;
	}
}
