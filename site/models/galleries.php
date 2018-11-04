<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die;

/**
 * Foo model.
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
class RSGallery2ModelGalleries extends JModelList
{
    /**
     * @var     object
     * @since   1.6
     */
    protected $state;
    /**/

    protected $_extension = 'com_rsgallery2';

    protected $_items = array();

    /**/
     protected $_total = null;
     protected $_pagination  = null;


    function __construct()
    {
        parent::__construct();
    }
    /**/

    /**
     * populate internal state
     *
     * @return void
     */
    protected function populateState($ordering = 'ordering', $direction = 'dsc')
    {
        // List state information.
        parent::populateState($ordering, $direction);

        /**/
        $app = JFactory::getApplication();
        // Get the job id
        //$input = $app->input;

        //$gid = $input->get('gid', '', 'INT');
        //$this->setState('images.galleryId', $gid);

        // Load the config parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        /**/
        // Load the list state.
        $this->setState('list.start', 0);
        // thumbs per page
        $limit = $params['galcountNrs'];
        $this->setState('list.limit', $limit);
        /**/

        $limitStart = $app->input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $limitStart);
        /**/
    }

    protected function getListQuery()
    {
        /**/
        //$galleryId = $this->getState('images.galleryId');

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select required fields
        $query->select('*')
            ->from($db->quoteName('#__rsgallery2_galleries'))
            ->order('ordering');

        return $query;
        /**/
    }


    /**
     * Method to get a list of articles.
     *
     * @return  mixed  An array of objects on success, false on failure.
     *
     * @since   1.6
     */
    public function getItems()
    {
        /**/
        // Get the items.
        $this->_items = parent::getItems();

        /**
         * // Convert them to a simple array.
         * foreach ($items as $k => $v)
         * {
         * $items[$k] = $v->term;
         * }
         * /**/

        /**/
        // Process pagination.
        $limit = (int)$this->getState('list.limit', 5); // ToDo: origin of list limit ?

        // Sets the total for pagination.
        $this->_total = count($this->_items);

        $items = $this->_items;
        if ($limit !== 0) {
            $start = (int)$this->getState('list.start', 0);

            $items = array_slice($this->_items, $start, $limit);
        }
        return $items;
        /**/
    }

}
