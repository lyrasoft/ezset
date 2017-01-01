<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

$fieldset = $data->fieldset;
?>
<fieldset id="addon-edit-fieldset-<?php echo $fieldset->name ?>" class="<?php echo $data->class ?>">
	<legend>
		<?php echo $fieldset->label ? JText::_($fieldset->label) : JText::_('COM_EZSET_EDIT_FIELDSET_' . $fieldset->name); ?>
	</legend>

	<?php foreach ($data->form->getFieldset($fieldset->name) as $field): ?>
		<div id="control_<?php echo $field->id; ?>">
			<?php echo $field->renderField() . "\n\n"; ?>
		</div>
	<?php endforeach;?>
</fieldset>
