<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

use Windwalker\DI\Container;
use Windwalker\Model\Filter\FilterHelper;
use Windwalker\Model\ListModel;

// No direct access
defined('_JEXEC') or die;

/**
 * Ezset Configs model
 *
 * @since 1.0
 */
class EzsetModelConfigs extends ListModel
{
	/**
	 * Only allow this fields to set in query.
	 *
	 * Override this property at component layer.
	 *
	 * @var  array
	 *
	 * @since  2.1
	 */
	protected $allowFields = array();

	/**
	 * Set field aliases to make correct query columns.
	 *
	 * Override this property at component layer.
	 *
	 * @var  array
	 *
	 * @since  2.1
	 */
	protected $fieldMapping = array();

	/**
	 * Component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'ezset';

	/**
	 * The URL option for the component.
	 *
	 * @var  string
	 */
	protected $option = 'com_ezset';

	/**
	 * The prefix to use with messages.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_EZSET';

	/**
	 * The model (base) name
	 *
	 * @var  string
	 */
	protected $name = 'configs';

	/**
	 * Item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'config';

	/**
	 * List name.
	 *
	 * @var  string
	 */
	protected $viewList = 'configs';

	/**
	 * Configure tables through QueryHelper.
	 *
	 * @return  void
	 */
	protected function configureTables()
	{
		$this->addTable('config', '#__ezset_configs')
			->addTable('category',  '#__categories', 'config.catid      = category.id')
			->addTable('user',      '#__users',      'config.created_by = user.id')
			->addTable('viewlevel', '#__viewlevels', 'config.access     = viewlevel.id')
			->addTable('lang',      '#__languages',  'config.language   = lang.lang_code');
	}

	/**
	 * The prepare getQuery hook
	 *
	 * @param JDatabaseQuery $query The db query object.
	 *
	 * @return  void
	 */
	protected function prepareGetQuery(\JDatabaseQuery $query)
	{
	}

	/**
	 * The post getQuery object.
	 *
	 * @param JDatabaseQuery $query The db query object.
	 *
	 * @return  void
	 */
	protected function postGetQuery(\JDatabaseQuery $query)
	{
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method will only called in constructor. Using `ignore_request` to ignore this method.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = 'ASC')
	{
		// Build ordering prefix
		if (!$ordering)
		{
			$table = $this->getTable('Config');

			$ordering = property_exists($table, 'ordering') ? 'config.ordering' : 'config.id';

			$ordering = property_exists($table, 'catid') ? 'config.catid, ' . $ordering : $ordering;
		}

		parent::populateState($ordering, $direction);
	}

	/**
	 * Process the query filters.
	 *
	 * @param JDatabaseQuery $query   The query object.
	 * @param array          $filters The filters values.
	 *
	 * @return  JDatabaseQuery The db query object.
	 */
	protected function processFilters(\JDatabaseQuery $query, $filters = array())
	{
		// If no state filter, set published >= 0
		if (!isset($filters['config.state']) && property_exists($this->getTable(), 'state'))
		{
			$query->where($query->quoteName('config.state') . ' >= 0');
		}

		return parent::processFilters($query, $filters);
	}

	/**
	 * Configure the filter handlers.
	 *
	 * Example:
	 * ``` php
	 * $filterHelper->setHandler(
	 *     'config.date',
	 *     function($query, $field, $value)
	 *     {
	 *         $query->where($field . ' >= ' . $value);
	 *     }
	 * );
	 * ```
	 *
	 * @param FilterHelper $filterHelper The filter helper object.
	 *
	 * @return  void
	 */
	protected function configureFilters($filterHelper)
	{
	}

	/**
	 * Configure the search handlers.
	 *
	 * Example:
	 * ``` php
	 * $searchHelper->setHandler(
	 *     'config.title',
	 *     function($query, $field, $value)
	 *     {
	 *         return $query->quoteName($field) . ' LIKE ' . $query->quote('%' . $value . '%');
	 *     }
	 * );
	 * ```
	 *
	 * @param SearchHelper $searchHelper The search helper object.
	 *
	 * @return  void
	 */
	protected function configureSearches($searchHelper)
	{
	}
}
