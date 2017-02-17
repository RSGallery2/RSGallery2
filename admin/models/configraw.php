<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('joomla.application.component.helper');

/**
 * Handle RAW display of configuration
 *
 *
 * @since 4.3.0
 */
//class Rsgallery2ModelConfigRaw extends JModelLegacy  // JModelForm // JModelAdmin // JModelList // JModelItem
//class Rsgallery2ModelConfigRaw extends JModelAdmin  // JModelForm
class Rsgallery2ModelConfigRaw extends JModelList
{
	public function getTable($type = 'Config', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	// save raw ...
	public function save()
	{
	    // ToDO: Move message to controller, return true or false

		$msg = "Rsgallery2ModelConfigRaw: ";

		$input = JFactory::getApplication()->input;
		//$jform = $input->get( 'jform', array(), 'ARRAY');
		$data = $input->post->get('jform', array(), 'array');

        // ToDo: Remove bad injected code

		$row = $this->getTable();
		foreach ($data as $key => $value)
		{
            // fill an array, bind and check and store ?
			$row->id    = null;
			$row->name  = $key;
			$row->value = $value;
			$row->id    = null;

			$row->check();
			$row->store();
		}

		return $msg;
	}
}
