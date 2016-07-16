<?php
// No direct access to this file
defined('_JEXEC') or die;

/**
 * CommentsList Model
 */
class Rsgallery2ModelComments extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			/**
			$config['filter_fields'] = array(
			'id', 'a.id',
			'item_id', 'a.item_id',
			'comment', 'a.comment',
			'user_name', 'a.user_name',
			'user_ip', 'a.user_ip',
			'hits', 'a.hits'
			);
			/**/

			/**
			$config['filter_fields'] = array(
			'id',
			'item_id',
			'comment',
			'user_name',
			'user_ip',
			'hits'
			);
			/**/

			/**/
			$config['filter_fields'] = array(
				'id','id',
				'user_id','user_id',
				'user_name','user_name',
				'user_ip','user_ip',
				'parent_id','parent_id',
				'item_id','item_id',
				'item_table','item_table',
				'datetime','datetime',
				'subject','subject',
				'comment','comment',
				'published','published',
				'checked_out','checked_out',
				'checked_out_time','checked_out_time',
				'ordering','ordering',
				'params','params',
				'hits','hits'
			);
			/**/
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = 'item_id, datetime', $direction = 'desc')
	{
		// $app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search',
			'', 'string');
		$this->setState('filter.search', $search);

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}


	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Query for all galleries.
		$actState =
			$this->getState(
				'list.select',
				'id, user_id, user_name, user_ip, parent_id, item_id, '
				. 'item_table, datetime, subject, comment, published, '
				. 'checked_out, checked_out_time, ordering, params, hits');

		$query->select($actState);
		$query->from('#__rsgallery2_comments');

		$search = $this->getState('filter.search');
		if(!empty($search)) {
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'comment LIKE ' . $search
				. ' OR user_name LIKE ' . $search
				. ' OR user_ip LIKE ' . $search
				. ' OR item_id LIKE ' . $search
			);
		}

/**
		$sort = $this->getState('list.ordering');
		$order = $this->getState('list.direction');
		$query->order($db->escape($sort) . ' ' . $db->escape($order));
/**/
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'item_id, datetime');
		$orderDirn = $this->state->get('list.direction', 'desc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));


//		echo '$query: ' . json_encode($query) . '<br>';


		return $query;
	}





}