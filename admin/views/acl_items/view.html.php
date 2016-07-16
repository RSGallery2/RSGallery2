<?php

defined( '_JEXEC' ) or die;

jimport('joomla.html.html.bootstrap');
// jimport('joomla.application.component.view');
// jimport('joomla.application.component.model');

// JModelLegacy::addIncludePath(JPATH_COMPONENT.'/models');

class Rsgallery2ViewAcl_items extends JViewLegacy
{

	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	
	protected $UserIsRoot;
	protected $sidebar;

	protected $items;
	protected $pagination;
	protected $state;

//	protected $rsgConfigData;

	//------------------------------------------------
	public function display ($tpl = null)
	{
		//--- get needed form data ------------------------------------------

		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();

//		global $rsgConfig;
//		$this->rsgConfigData = $rsgConfig;

		$this->items = $this->get('Items');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}


//		// different toolbar on different layouts
//		$Layout = JFactory::getApplication()->input->get('layout');
//
		//$this->addToolbar ($Layout);
		$this->addToolbar ();
		$this->sidebar = JHtmlSidebar::render ();

		parent::display ($tpl);

        return;
	}

	/**
	 * Checks if user has root status (is re.admin')
	 *
	 * @return	bool
	 */		
	function CheckUserIsRoot ()
	{
		$user = JFactory::getUser();
		$canAdmin = $user->authorise('core.admin');
		return $canAdmin;
	}

	protected function addToolbar ($Layout='default')
	{
		switch ($Layout)
		{
			default:
				JToolBarHelper::title(JText::_('COM_RSGALLERY2_ACCESS_CONTROL'), 'eye-close');
//				JToolBarHelper::apply('config.apply');
//				JToolBarHelper::save('config.save');
//				JToolBarHelper::cancel('config.cancel');
				break;
		}

	}
}

