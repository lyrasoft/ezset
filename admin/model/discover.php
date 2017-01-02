<?php
/**
 * Part of Component Ezset files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

use Windwalker\DataMapper\DataMapper;
use Windwalker\DI\Container;
use Windwalker\Model\Filter\FilterHelper;
use Windwalker\Model\ListModel;

// No direct access
defined('_JEXEC') or die;

/**
 * Ezset Discover model
 *
 * @since 1.0
 */
class EzsetModelDiscover extends ListModel
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
	protected $name = 'discover';

	/**
	 * Item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'discover';

	/**
	 * List name.
	 *
	 * @var  string
	 */
	protected $viewList = 'discover';

	/**
	 * getItems
	 *
	 * @return  array
	 */
	public function getItems()
	{
		return $this->fetch('items', function ()
		{
		    $found = \Ezset\Library\Addon\AddonHelper::findAddons();

		    $keep = array_diff(array_keys($found), $this->getInstalled()->name);

		    return array_values(array_intersect_key($found, array_flip($keep)));
		});
	}

	/**
	 * getInstalled
	 *
	 * @return  mixed|\Windwalker\Data\DataSet
	 */
	public function getInstalled()
	{
		$mapper = new DataMapper('#__ezset_addons');

		return $mapper->findAll();
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
		return parent::processFilters($query, $filters);
	}

	/**
	 * Configure the filter handlers.
	 *
	 * Example:
	 * ``` php
	 * $filterHelper->setHandler(
	 *     'discover.date',
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
	 *     'discover.title',
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
