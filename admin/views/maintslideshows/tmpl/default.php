<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

// JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip');

global $Rsg2DebugActive;

JHtml::_('formbehavior.chosen', 'select');


function tabHeader ($sliderName)
{
    // ? Sanitize name ?


    echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName); //, true);
	//echo JHtml::_('bootstrap.addTab', 'slidersTab', $sliderName, $sliderName);
    //echo " // start tab " . $sliderName;
}

function tabContent ($xmlFileInfo)
{
	echo '<div class="well">';
	// $xmlFileInfo->name;
    //<h3><?php echo $this->item->title;</h3>

    echo '<h3>Content: ' . $xmlFileInfo->name . '<h3>';
    echo '<h4>Form Fields (XML Config part of templateDetails.xml -> J2.5++ style)</h4>';

	$config = $xmlFileInfo->formFields->config->fields;

	/**
	$formFieldsXml = $config->asXML();
	echo '<div class="control-group">';
	echo '    <label class="control-label" for="templateDetails_xml">Email</label>';
	echo '    <div class="controls">';
	echo '        <textarea  id="templateDetails_xml" class="input-xxlarge" rows="20"  readonly>'. $formFieldsXml . '</textarea>';
	echo '    </div>';
	echo '</div>';
	/**/

	/**
	$formFieldsXml = $config->asXML();
	echo '<div class="control-group">';
	echo '    <label class="control-label" for="templateDetails_xml">Email</label>';
	echo '    <div class="controls">';
	echo '        <textarea  id="templateDetails_xml" class="span12" rows="20"  readonly>'. $formFieldsXml . '</textarea>';
	echo '    </div>';
	echo '</div>';
	/**/

	/**
	$formFieldsXml = $config->asXML();
	echo '<div class="control-group">';
	//echo '    <label class="control-label" for="templateDetails_xml">Email</label>';
	echo '    <div class="controls">';
	echo '        <textarea  id="templateDetails_xml" class="span12" rows="20"  readonly>'. $formFieldsXml . '</textarea>';
	echo '    </div>';
	echo '</div>';
	/**/

	/**
	$formFieldsXml = $config->asXML();
	echo '<div class="control-group">';
	//echo '    <label class="control-label" for="templateDetails_xml">Email</label>';
	//echo '    <div class="controls">';
	echo '        <textarea  id="templateDetails_xml" class="span12" rows="20"  readonly>'. $formFieldsXml . '</textarea>';
	//echo '    </div>';
	echo '</div>';
	/**/

    // echo '<hr>';

	//echo '<h4>Test form fields</h4>';

	$form = new JForm ($xmlFileInfo->name);

	//--- add parent form element ------------------------
	$xmlForm = new SimpleXMLElement('<form></form>');
	SimpleXMLElement_append($xmlForm, $config);
	//$xmlFormAsXml = $xmlForm->asXML();

	$form->load($xmlForm->asXML());
	echo $form->renderFieldset('advanced');

	echo '<hr>';

	echo '<h4>file params.ini: parameter=values </h4>';

	$testRegistry = $xmlFileInfo->parameterValues;
    $parameterLines = $testRegistry->toString ('INI');
	// echo $parameterLines;

    echo '<div class="control-group">';

	echo '    <div class="control-label">';
	echo '        <label  id="params_ini-lbl" for="params_ini"  class="hasPopover" ';
	echo '           title="" ';
	echo '           data-original-title="templateDetails.xml" ';
	echo '           data-content="Content of file templateDetails.xml in slideshow folder" ';
	echo '        >';
	echo 'templateDetails.xml';
	echo '        </label>';
	echo '    </div>';


	echo '    <div class="controls">';
    echo '        <textarea id="params_ini" class="input-xxlarge" rows="20">'. $parameterLines . '</textarea>';
	echo '    </div>';
    echo '</div>';

    // button to submit the changed data
    slideshowButton ($xmlFileInfo->name);

	// echo json_encode($xmlFileInfo) ;
	//echo $this->form->renderFieldset('regenerateGallerySelection');
    echo '</div>'; // well
}

function tabFooter ($sliderName)
{
	echo JHtml::_('bootstrap.endTab');
	//echo " //end tab " . $sliderName;
}

function slideshowButton ($sliderName)
{

    echo '<!-- Action button ' . $sliderName . ' -->';
    echo '<div class="form-actions">';
    echo '    <button type="button" class="btn btn-primary"';
    echo '        onclick="Joomla.submitbutton(\'maintslideshows.save\')">';
    echo          JText::_('COM_RSGALLERY2_MAINT_SLIDESHOW_SAVE');
    echo '    </button>';
    echo '</div>';

}

function SimpleXMLElement_append($parent, $child)
{
	// get all namespaces for document
	$namespaces = $child->getNamespaces(true);

	// check if there is a default namespace for the current node
	$currentNs = $child->getNamespaces();
	$defaultNs = count($currentNs) > 0 ? current($currentNs) : null;
	$prefix = (count($currentNs) > 0) ? current(array_keys($currentNs)) : '';
	$childName = strlen($prefix) > 1
		? $prefix . ':' . $child->getName() : $child->getName();

	// check if the value is string value / data
	if (trim((string) $child) == '') {
		$element = $parent->addChild($childName, null, $defaultNs);
	} else {
		$element = $parent->addChild(
			$childName, htmlspecialchars((string)$child), $defaultNs
		);
	}

	foreach ($child->attributes() as $attKey => $attValue) {
		$element->addAttribute($attKey, $attValue);
	}
	foreach ($namespaces as $nskey => $nsurl) {
		foreach ($child->attributes($nsurl) as $attKey => $attValue) {
			$element->addAttribute($nskey . ':' . $attKey, $attValue, $nsurl);
		}
	}

	// add children -- try with namespaces first, but default to all children
	// if no namespaced children are found.
	$children = 0;
	foreach ($namespaces as $nskey => $nsurl) {
		foreach ($child->children($nsurl) as $currChild) {
			SimpleXMLElement_append($element, $currChild);
			$children++;
		}
	}
	if ($children == 0) {
		foreach ($child->children() as $currChild) {
			SimpleXMLElement_append($element, $currChild);
		}
	}
}

?>

<div id="slidshow-edit" class="clearfix">
	<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=maintSlideshows'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

                <legend><?php echo JText::_('COM_RSGALLERY2_SLIDESHOWS_CONFIGURATION'); ?></legend>
                <div><?php echo JText::_('COM_RSGALLERY2_SLIDESHOWS_CONFIGURATION_INFO'); ?></div>
                <br>

				<?php
                /**
                echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'name'));
                echo '//start tab set<br>';

				echo JHtml::_('bootstrap.addTab', 'myTab', 'name', JText::_('COM_EXAMPLE_NAME'));
                echo '//start tab pane 1';
                echo '<h3>' . 'title' . '</h3>';
                echo '<p>Author: ' . 'author' . '</p>';
				echo JHtml::_('bootstrap.endTab');
                echo '//end tab pane 1<br>';

				echo JHtml::_('bootstrap.addTab', 'myTab', 'desc', JText::_('COM_EXAMPLE_DESCRIPTION'));
                echo '//start tab pane 2';
				echo '<h3>' . 'description' . '</h3>';
				echo JHtml::_('bootstrap.endTab');
                echo '//end tab pane 2<br>';

				echo JHtml::_('bootstrap.addTab', 'myTab', 'price', JText::_('COM_EXAMPLE_PRICE'));
                echo '//start tab pane 3';
				echo '<h3>' . 'price' . '</h3>';
				echo JHtml::_('bootstrap.endTab');
                echo '//end tab pane 3<br>';

				echo JHtml::_('bootstrap.endTabSet');
                echo '//end tab set';

                echo '<hr>';
                /**/
                ?>

                <?php

                /**/
                if ( ! empty ($this->slidesConfigFiles))
                {
	                //$form = new JForm ($this->slidesConfigFiles->form);
	                $form = $this->formX;
	                echo $form->renderFieldset('maintslideshows');

	                // activate first (?last ?) element
                    // toDo: Last used ....
                    $slidesCount = count ($this->slidesConfigFiles);
                    //$xmlFileInfo = $this->slidesConfigFiles [$slidesCount-1];
                    //$xmlFileInfo = $this->slidesConfigFiles [$slidesCount-2];
                    $xmlFileInfo = $this->slidesConfigFiles [0];
                    $activeName = $xmlFileInfo->name;

                    /**/
	                $xmlFileInfo = $this->slidesConfigFiles [1];
	                $sliderName = $xmlFileInfo->name;

	                echo JHtml::_('bootstrap.startTabSet', 'slidersTab', array('active' => $sliderName));
	                //echo '//start tab set<br>';

	                foreach ($this->slidesConfigFiles as $xmlFileInfo)
	                {
	                    if ( ! empty ($xmlFileInfo->formFields))
	                    {
		                    $sliderName = $xmlFileInfo->name;
		                    // extract parameter
		                    tabHeader($sliderName);

		                    tabContent($xmlFileInfo);

		                    tabFooter($sliderName);
	                    }
	                }
	                echo JHtml::_('bootstrap.endTabSet');
	                //echo '//end tab set';
	                /**/
				?>

                <input type="hidden" value="" name="task">

				<?php

                    echo JHtml::_('form.token');

				} //    empty ($this->slidesConfigFiles))

                ?>

			</form>
		</div>
		<div id="loading"></div>
	</div>



</div>
