<?php
/**
 * Part of joomla34 project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Ezset\Database;

/**
 * The Backup class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Backup
{
	/**
	 * export
	 *
	 * @return  string
	 */
	public static function export()
	{
		$db = \JFactory::getDbo();

		$tables = $db->getTableList();

		$sql = array();

		foreach ($tables as $table)
		{
			$create = $db->setQuery('SHOW CREATE TABLE ' . $table)->loadRow();

			$sql[] = 'DROP TABLE IF EXISTS ' . $table;
			$sql[] = $create[1];

			static::exportRows($table, $sql);
		}

		return implode(";\n", $sql) . ';';
	}

	/**
	 * exportRows
	 *
	 * @param string $table
	 * @param string $sql
	 *
	 * @return  void
	 */
	public static function exportRows($table, &$sql)
	{
		$db = \JFactory::getDbo();

		$list = $db->setQuery('SELECT * FROM ' . $table)->getIterator();

		foreach ($list as $row)
		{
			$query = $db->getQuery(true);

			$row = $db->q(get_object_vars($row));

			$query->insert($table)
				->values(implode(', ', $row));

			$sql[] = (string) $query;
		}
	}
}
