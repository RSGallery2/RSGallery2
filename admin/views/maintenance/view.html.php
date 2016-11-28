<?php

defined( '_JEXEC' ) or die;

jimport ('joomla.html.html.bootstrap');
jimport('joomla.application.component.view');

class Rsgallery2ViewMaintenance extends JViewLegacy
{	
	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to 
	// the global config
	protected $UserIsRoot;
	
	protected $dangerActive;
	protected $rawDbActive;
	protected $upgradeActive;
	protected $testActive;
	protected $developActive;
	protected $debugActive;
	
	protected $sidebar;

	//------------------------------------------------
	public function display ($tpl = null)
	{
		global $Rsg2DevelopActive;
		global $rsgConfig;

		// on develop show open tasks if existing
		if(!empty ($Rsg2DevelopActive)) {
			// echo '<span style="color:red">Task: </span><br><br>';
		}

		//--- get needed data ------------------------------------------
		
		// Check rights of user
		$this->UserIsRoot = $this->CheckUserIsRoot ();
		$this->debugActive = $rsgConfig->get('debug');

		$this->rawDbActive = true; // false / true;
		$this->dangerActive = true; // false / true;
		$this->upgradeActive = true; // false / true;
		$this->testActive = true; // false / true;
		$this->developActive = true; // false / true;

		//--- begin to display --------------------------------------------
		
		$this->addToolbar ();
		$this->sidebar = JHtmlSidebar::render ();

		parent::display ($tpl);
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

	protected function addToolbar ()
	{
		JToolBarHelper::title(JText::_('COM_RSGALLERY2_MAINTENANCE'), 'screwdriver'); // 'maintenance');
	}
}


