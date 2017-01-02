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
<table id="discoverList" class="table table-striped adminlist">

<!-- TABLE HEADER -->
<thead>
<tr>
	<!--CHECKBOX-->
	<th width="1%" class="center">
		<?php echo JHtml::_('grid.checkAll'); ?>
	</th>

	<!--STATE-->
	<th width="5%" class="nowrap center">
		<?php echo JText::_('COM_EZSET_DISCOVER_ACTION_INSTALL'); ?>
	</th>

	<!--TITLE-->
	<th class="center">
		<?php echo JText::_('JGLOBAL_TITLE'); ?>
	</th>

	<!--NAME-->
	<th class="center">
		<?php echo JText::_('COM_EZSET_ADDON_NAME'); ?>
	</th>

	<!--CLIENT-->
	<th class="center">
		<?php echo JText::_('COM_EZSET_ADDON_CLIENT'); ?>
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
	<tr class="discover-row">
		<!--CHECKBOX-->
		<td class="center">
			<?php echo JHtml::_('grid.id', $i, $item->name); ?>
		</td>

		<!--STATE-->
		<td class="center">
			<div class="btn-group">
				<a class="btn btn-micro hasTooltip" href="javascript:void(0);"
					onclick="return listItemTask('cb<?php echo $i; ?>', 'discover.install')" title="<?php echo JText::_('COM_EZSET_DISCOVER_ACTION_INSTALL'); ?>">
					<span class="icon-upload"></span>
				</a>
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
				<?php echo $item->path; ?>
			</small>
		</td>

		<td>
			<?php echo $item->name; ?>
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
	</tr>
<?php endforeach; ?>
</tbody>
</table>
