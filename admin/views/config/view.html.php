<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2020 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

jimport('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');
jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/RSGallery2.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/sidebarLinks.php';

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/includes/version.rsgallery2.php');

/**
 * yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
 * 
 * zek20xw813rj3
 *
 * @since 4.3.0
 */
class Rsgallery2ViewConfig extends JViewLegacy
{
	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to
	// the global config
	protected $form;
	protected $item;
	protected $sidebar;

	protected $rsgConfigData;
	protected $UserIsRoot;

	protected $rsgVersion;
//	protected $allowedFileTypes;

	protected $configVars;

    //------------------------------------------------
	/**
	 * @param null $tpl
	 *
	 * @since 4.3.0
	*/
	public function display($tpl = null)
	{
		global $Rsg2DevelopActive, $rsgConfig;

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">Task: Save should not leave program, rename save in controller as it is used by Raw ...</span><br><br>';
		}

		//--- get needed form data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot();

		$this->form = $this->get('Form');
//		$this->item = $this->get('Item');

		$rsgVersion       = new rsgalleryVersion();
		$this->rsgVersion = $rsgVersion->getLongVersion();


		$Layout = JFactory::getApplication()->input->get('layout');

		// get config from different sources

		$this->configVars = array ();

		try
		{
			// Not Old sql config table
			$FoundIdx = strpos(strtolower($Layout), 'old');
			if (empty($FoundIdx))
			{
				$this->configVars = JComponentHelper::getParams('com_rsgallery2');
			}
			else
			{
				// Sql param ...
				//$this->configVars =  new rsgConfig();
				$this->configVars = $rsgConfig;
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error collecting config Data for: "' . $Layout . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

//		global $rsgConfig;
//		$this->rsgConfigData = $rsgConfig;

		//--- get needed extra config data ------------------------------------------

//		$this->rsgVersion = $rsgConfig->version; // "Version 04.01.00";
//		 ToDo: Check for using List in XML ???
//		$this->allowedFileTypes = imgUtils::allowedFileTypes ();

//		$this->configVars = get_object_vars($this->rsgConfigData);
//		$this->form->bind ($this->configVars);

		//--- begin to display --------------------------------------------

//		Rsg2Helper::addSubMenu('rsg2'); 

		// 2020.10.28 php 7.2 -> 7.4
        //// Check for errors.
        //if (count($errors = $this->get('Errors')))
        //{
        //    throw new RuntimeException(implode('<br />', $errors), 500);
        //}

        // Check for errors.
		if ($errors = $this->get('Errors'))
		{
			if (count($errors))
			{
				throw new RuntimeException(implode('<br />', $errors), 500);
			}
		}

		// Assign the Data
		// $this->form = $form;

		//--- tool  bar -------------------------

		// different toolbar on different layouts
		$this->addToolbar($Layout);

		//--- side  bar -------------------------

        $View = JFactory::getApplication()->input->get('view');
        RSG2_SidebarLinks::addItems($View, $Layout);
        $this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);

		return;
	}

	/**
	 * Checks if user has root status (is re.admin')
	 *
	 * @return    bool
	 * @since 4.3.0
	 */
	function CheckUserIsRoot()
	{
		$user     = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');

		return $canAdmin;
	}

	/**
	 * @param string $Layout
	 *
	 * @since 4.3.0
	*/
	protected function addToolbar($Layout = 'default')
	{
		global $Rsg2DevelopActive;

		// on develop show open tasks if existing
		if (!empty ($Rsg2DevelopActive))
		{
			echo '<span style="color:red">'
				. '* rename save in controller as it is used by Raw <br>'
				. '*  Add root gallery section<br>'
				. '* Config -> update gallery selection preselect last used gallery ? show combo opened for n entries <br>'
				. '* Config -> update gallery selection preselect latest gallery  (User input ...) <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
				. '</span><br><br>';
		}

		switch ($Layout)
		{
			case 'RawView':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'), 'screwdriver');
				JToolBarHelper::cancel('config.cancel_rawView');
				break;
			case 'RawEdit':
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'screwdriver');
				JToolBarHelper::apply('config.apply_rawEdit');
				JToolBarHelper::save('config.save_rawEdit');
				JToolBarHelper::cancel('config.cancel_rawEdit');
				JToolbarHelper::custom('config.copy_rawEditFromOld', 'next', 'next', 'Copy old to new configuration data', false);
				JToolbarHelper::custom('config.save_rawEditAndCopy2Old', 'previous', 'previous', 'Save and copy to old configuration data', false);
				JToolbarHelper::custom('config.save_rawEdit2Text', 'file-2', 'file-2', 'Save to text file', false);
				JToolbarHelper::custom('config.read_rawEdit2Text', 'file', 'file', 'Read from text file', false);
				JToolbarHelper::custom('config.remove_OldConfigData', 'next', 'next', 'Remove old configuration data', false);
				break;
			case 'RawViewOld':
				JToolBarHelper::title('OLD: ' . JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'), 'screwdriver');
				JToolBarHelper::cancel('config.cancel_rawViewOld');
				break;
			case 'RawEditOld':
				JToolBarHelper::title('OLD: ' . JText::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . JText::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'screwdriver');
				JToolBarHelper::apply('config_j25.apply_rawEditOld');
				JToolBarHelper::save('config_j25.save_rawEditOld');
				JToolBarHelper::cancel('config.cancel_rawEdit');
				JToolbarHelper::custom('config_j25.save_rawEditOldAndCopy2New', 'previous', 'previous', 'Save (old) and copy to new configuration data', false);
				JToolbarHelper::custom('config_j25.save_rawEditOld2Text', 'file-2', 'file-2', 'Save (old) to text file', false);
				JToolbarHelper::custom('config_j25.read_rawEditOld2Text', 'file', 'file', 'Read (old) from text file', false);
				JToolbarHelper::custom('config.remove_OldConfigData', 'next', 'next', 'Remove old configuration data', false);
				break;
			// case 'default':
			default:
				JToolBarHelper::title('OLD: ' . JText::_('COM_RSGALLERY2_CONFIGURATION'), 'equalizer');

				JToolBarHelper::apply('config.apply');
				JToolBarHelper::save('config.save');
				JToolBarHelper::cancel('config.cancel');

				break;
		}
	}

}


