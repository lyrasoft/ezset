<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// No direct access
use Windwalker\Dom\SimpleXml\XmlHelper;

defined('_JEXEC') or die;

JHtmlBootstrap::tooltip();
JHtmlFormbehavior::chosen('select');
JHtmlBehavior::formvalidator();

/**
 * Prepare data for this template.
 *
 * @var $this      \Windwalker\View\Engine\PhpEngine
 * @var $container \Windwalker\DI\Container
 * @var $data      \Windwalker\Data\Data
 * @var $item      \stdClass
 * @var $form      \JForm
 */
$container = $this->getContainer();
$form      = $data->form;
$item      = $data->item;

$fields = $form->getXml()->xpath('//fields[@name]');
?>
<!-- Validate Script -->
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'config.edit.cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
</script>

<div id="ezset" class="windwalker config edit-form row-fluid">
	<?php if (!empty($this->data->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->data->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else: ?>
	<div id="j-main-container" class="span12">
	<?php endif;?>
		<form action="<?php echo JUri::getInstance(); ?>"  method="post" name="adminForm" id="adminForm"
			class="form-validate" enctype="multipart/form-data">

			<?php if (count($fields)): ?>

				<?php echo JHtmlBootstrap::startTabSet('configEditTab', array('active' => XmlHelper::get($fields[0], 'name', 'basic'))); ?>

				<?php
				foreach ($fields as $field)
				{
					$name = XmlHelper::get($field, 'name', 'basic');

					echo $this->loadTemplate('form', array('tab' => $name, 'group' => $field));
				}
				?>

				<?php echo JHtmlBootstrap::endTabSet(); ?>

			<?php endif; ?>

			<!-- Hidden Inputs -->
			<div id="hidden-inputs">
				<input type="hidden" name="option" value="com_ezset" />
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="addon" value="<?php echo $this->escape($data->addon); ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</form>
	</div>
</div>
