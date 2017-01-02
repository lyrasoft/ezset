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

$fieldset = $data->fieldset;

// Label
$label = $fieldset->label ? : sprintf('COM_EZSET_ADDON_%s_FIELDSET_%s', strtoupper($data->addon), strtoupper($fieldset->name));
?>
<div class="row-flui">
	<fieldset id="config-edit-fieldset-<?php echo $fieldset->name ?>" class="<?php echo $data->class ?>">
		<legend>
			<?php echo JText::_($label); ?>
		</legend>
		<div class="span7">
			<?php foreach ($data->form->getFieldset($fieldset->name) as $field): ?>
				<div id="control_<?php echo $field->id; ?>">
					<?php echo $field->renderField() . "\n\n"; ?>
				</div>
				<?php if (JDEBUG): ?>
				    <code>
					    <?php echo $data->addon . '.' . $field->group . '.'  . $field->fieldname; ?>
				    </code>
				<?php endif; ?>
			<?php endforeach;?>
		</div>
		
		<?php if ($file = AddonHelper::getFieldsetDescriptionFile($data->addon, $data->tab, $fieldset->name)): ?>
		    <?php echo $this->loadTemplate('description', array('file' => $file)) ?>
		<?php endif; ?>
	</fieldset>
</div>
