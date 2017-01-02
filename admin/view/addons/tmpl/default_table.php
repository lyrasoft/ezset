<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

use Windwalker\Data\Data;

// No direct access
defined('_JEXEC') or die;

// Prepare script
JHtmlBehavior::multiselect('adminForm');

/**
 * Prepare data for this template.
 *
 * @var $container \Windwalker\DI\Container
 * @var $data      \Windwalker\Data\Data
 * @var $asset     \Windwalker\Asset\AssetManager
 * @var $grid      \Windwalker\View\Helper\GridHelper
 * @var $date      \JDate
 */
$container = $this->getContainer();
$asset     = $container->get('helper.asset');
$grid      = $data->grid;
$date      = $container->get('date');

// Set order script.
$grid->registerTableSort();
?>

<!-- LIST TABLE -->
<table id="addonList" class="table table-striped adminlist">

<!-- TABLE HEADER -->
<thead>
<tr>
	<!--SORT-->
	<th width="1%" class="nowrap center hidden-phone">
		<?php echo $grid->orderTitle(); ?>
	</th>

	<!--CHECKBOX-->
	<th width="1%" class="center">
		<?php echo JHtml::_('grid.checkAll'); ?>
	</th>

	<!--STATE-->
	<th width="5%" class="nowrap center">
		<?php echo $grid->sortTitle('JSTATUS', 'addon.state'); ?>
	</th>

	<!--TITLE-->
	<th class="center">
		<?php echo JText::_('JGLOBAL_TITLE'); ?>
	</th>

	<!--NAME-->
	<th class="center" width="5%">
		<?php echo JText::_('COM_EZSET_ADDON_NAME'); ?>
	</th>

	<!--CLIENT-->
	<th class="center" width="5%">
		<?php echo JText::_('COM_EZSET_ADDON_CLIENT'); ?>
	</th>

	<!--ID-->
	<th width="1%" class="nowrap center">
		<?php echo $grid->sortTitle('JGRID_HEADING_ID', 'addon.id'); ?>
	</th>
</tr>
</thead>

<!--PAGINATION-->
<tfoot>
<tr>
	<td colspan="15">
		<div class="pull-left">
			<?php echo $data->pagination->getListFooter(); ?>
		</div>
	</td>
</tr>
</tfoot>

<!-- TABLE BODY -->
<tbody>
<?php foreach ($data->items as $i => $item)
	:
	// Prepare data
	$item = new Data($item);

	// Prepare item for GridHelper
	$grid->setItem($item, $i);
	?>
	<tr class="addon-row" sortable-group-id="<?php echo $item->catid; ?>">
		<!-- DRAG SORT -->
		<td class="order nowrap center hidden-phone">
			<?php echo $grid->dragSort(); ?>
		</td>

		<!--CHECKBOX-->
		<td class="center">
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>

		<!--STATE-->
		<td class="center">
			<div class="btn-group">
				<!-- STATE BUTTON -->
				<?php echo $grid->state() ?>
			</div>
		</td>

		<!--TITLE-->
		<td class="has-context quick-edit-wrap">
			<div class="item-title">
				<!-- Title -->
				<?php echo $this->escape($item->title); ?>
			</div>

			<!-- Sub Title -->
			<small class="muted">
				<?php echo $this->escape($item->path); ?>
			</small>
		</td>

		<!--NAME-->
		<td>
			<?php echo $this->escape($item->name); ?>
		</td>

		<td>
			<?php if ($item->client === 'site'): ?>
				<span class="label label-success">
				    <?php echo JText::_('JSITE'); ?>
			    </span>
			<?php else: ?>
				<span class="label label-info">
					<?php echo JText::_('JADMINISTRATOR'); ?>
				</span>
			<?php endif; ?>
		</td>

		<!--ID-->
		<td class="center">
			<?php echo $item->id; ?>
		</td>

	</tr>
<?php endforeach; ?>
</tbody>
</table>
