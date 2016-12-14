<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$published = $this->state->get('filter.published');
?>

<div class="row-fluid">
    <?php
    /**
	<div class="control-group span6">
		<div class="controls">
			<?php echo JHtml::_('batch.language'); ?>
		</div>
	</div>
	<div class="control-group span6">
		<div class="controls">
			<?php echo JHtml::_('batch.access'); ?>
		</div>
	</div>
</div>
     */
    ?>
<div class="row-fluid">
	<?php if ($published >= 0) : ?>
		<?php // <div class="pull-right"> ?>
        <div class=''>
            <?php
            // Specify gallery for move and copy
            echo $this->form->renderFieldset('Select4MoveCopy');
            ?>
        </div>
	    <?php /*
        <div class="control-group span6">
			<div class="controls">
				<?php echo JHtml::_('batch.item', 'com_content'); ?>
			</div>
		</div>
        /**/
		?>
	<?php endif; ?>
<?php
/**
	<div class="control-group span6">
		<div class="controls">
			<?php echo JHtml::_('batch.tag'); ?>
		</div>
	</div>
/**/
?>
</div>