<?php
/**
 * @package     
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2016 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die();
 
/**
 * Hello Table class
 *
 * @since  0.0.1
 */
class Rsgallery2TableComment extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver &$db A database connector object
	 */
	function __construct(&$db)
	{
		// id, name, value
		parent::__construct('#__rsgallery2_comments', 'id', $db);
	}

	public function bind($array, $ignore = array())
	{
		return parent::bind($array, $ignore);
	}

	public function store($updateNulls = false)
	{

		return parent::store($updateNulls);
	}
}