<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_rsgallery2/views/uploadFileProperties/css/uploadFileProperties.css');

JHtml::_('bootstrap.tooltip');

//JText::script('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN');
?>

<div id="uploadFileProperties" class="clearfix">
    <?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
    <?php else : ?>
        <div id="j-main-container">
    <?php endif; ?>

        <form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=UploadFileProperties.'); ?>"
              method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"
              class="form-validate">

            <legend><?php echo JText::_('COM_RSGALLERY2_PROPERTIES_UPLOADED_FILES'); ?></legend>

	        <?php if (empty($this->fileData)) : ?>

                <div class="alert">
                    <?php echo JText::_('COM_RSGALLERY2_NO_FILES_FOUND_TO_PROCESS'); ?>
                </div>
                 <div class="alert alert-info">
                    <?php echo JText::_('COM_RSGALLERY2_ACTION_ON_MISSING_UPLOADED_FILES'); ?>
                </div>

            <?php else : ?>
                <?php /**
                <ul class="thumbnails">
                    <?php foreach($this->fileData as $file) : ?>
                        <li class="span3">
                            <div class="thumbnail">
                                <div class='rsg-container'>
                                    <img data-src="holder.js/200x180" src="<?php echo $file; ?>" class="img-polaroid rsg-image" alt=""
                                         style="width: 200px; height: 180px; max-width: 90%; ">
                                </div>
                                <div class="caption" >
                                    <small><?php echo basename($file);?>"</small><br>
                                    Title:
                                    <br>
                                    Gallery:
                                    <select id="category" name="category[]" class="inputbox" size="1" style="max-width: 90%; ">
                                        <option value="-1">Select galleries</option>
                                        <option value="1">USER_X2</option>
                                    </select><br><input name="ptitle[]" size="15" aria-invalid="false" type="text" style="max-width: 90%; ">
                                    Description:
                                    <!--textarea cols="15" rows="2" name="descr[]"></textarea-->
                                    <textarea cols="15" rows="2" name="descr[]" style="max-width: 90%; "></textarea>

                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                /**/
                ?>

                <ul class="thumbnails">

                    <?php foreach($this->fileData as $file) : ?>

                        <li class="span3">
                            <div class="thumbnail">
                                <div class='rsg-container'>
                                    <img data-src="holder.js/200x180" src="<?php echo $file; ?>" class="img-polaroid rsg-image" alt=""
                                         style="width: 200px; height: 180px; max-width: 90%; ">
                                </div>

                                <div class="caption" >
                                    <small><?php echo basename($file);?>"</small><br>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="title[]"><?php echo JText::_('COM_RSGALLERY2_TITLE'); ?></label>
                                    <div class="controls">
                                        <!--input type="text" id="inputEmail" placeholder="Email"-->
                                        <input name="title[]" size="15" aria-invalid="false" type="text" style="max-width: 90%; ">
                                    </div>
                                </div>

                                <?php if (empty($this->isInOneGallery)) : ?>
                                    <!-- Seperate gallery for each image -->
                                    <div class="control-group" >
                                        <label class="control-label" for="galleryID"><?php echo JText::_('COM_RSGALLERY2_GALLERY'); ?>(1)</label>
                                        <div class="controls">
                                            <input type="text" name="galleryID" style="max-width: 90%; " value="?<?php echo $this->galleryId;?>?">
                                        </div>
                                    </div>

                                <?php else : ?>
                                    <!-- One gallery for all. Disable input -->
                                    <div class="control-group">
                                        <label class="control-label" for="galleryID"><?php echo JText::_('COM_RSGALLERY2_GALLERY'); ?>(2)</label>
                                        <div class="controls">
                                            <input type="text" name="galleryID" placeholder="Email" style="max-width: 90%; "  disabled>
                                        </div>
                                    </div>

                                <?php endif; ?>

                                <div class="control-group">
                                    <label class="control-label" for="descr[]"><?php echo JText::_('COM_RSGALLERY2_DESCRIPTION'); ?></label>
                                    <div class="controls">
                                        <textarea cols="15" rows="" name="descr[]" style="max-width: 90%; " placeholder="Text input"></textarea>
                                    </div>
                                </div>


                                <input  type="hidden" name="FileName[]" value="?<?php echo $file ;?>?">

                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
	        <?php endif; ?>


            session id ....


            <input type="hidden" value="com_rsgallery2" name="option">
            <input type="hidden" value="" name="task">
            <input type="hidden" value="<?php $this->galleryId ?>" name="gallery_id">

            <?php echo JHtml::_('form.token'); ?>
        </form>
        <div id="loading"></div>
    </div>
</div>