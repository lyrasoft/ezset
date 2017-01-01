<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

use Windwalker\Dom\SimpleXml\XmlHelper;

/** @var \JForm $form */
$form = $data->form;

$tab       = $data->tab;
$fieldsets = $form->getFieldsets($data->tab);

// Label
$label = XmlHelper::get($data->group, 'label') ? : sprintf('COM_EZSET_ADDON_%s_TAB_%s', strtoupper($data->addon), strtoupper($tab));
?>

<?php echo JHtmlBootstrap::addTab('configEditTab', $tab, JText::_($label)) ?>

	<?php foreach ($fieldsets as $fieldset => $fieldsetData): ?>
		<?php $class = \Windwalker\Helper\ArrayHelper::getValue($fieldsetData, 'class', 'form-horizontal') ?>
		<?php echo $this->loadTemplate('fieldset', array('fieldset' => $fieldsetData, 'class' => $class)); ?>
	<?php endforeach; ?>

<?php echo JHtmlBootstrap::endTab(); ?>
