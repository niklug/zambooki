<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	applications	An array of applications object
 */
defined('_JEXEC') or die();
?>

<script type="text/javascript">
joms.jQuery(document).ready(function()
{
	joms.editLayout.activate();
});
</script>

<div class="cLayout cApplications joms-apps">

	<div class="cSidebar">
	
		<?php echo $appItems['sidebar-top-core']; ?>
		<div id="pos-profile-sidebar-top" class="app-position">
			<?php echo $appItems['sidebar-top']; ?>
		</div>

		<div class="app-add">
			<a class="app-action-add cFloat-R" href="javascript: void(0)" onclick="joms.editLayout.browse('sidebar-top');">
				<i class="com-icon-add"></i>
				<span><?php echo JText::_('COM_COMMUNITY_ADD_APPLICATIONS'); ?></span>
			</a>
		</div>
		
		<div class="app-item app-core"><?php echo JText::_('COM_COMMUNITY_ABOUT_ME'); ?></div>
		<div class="app-item app-core"><?php echo JText::_('COM_COMMUNITY_FRIENDS'); ?></div>

		<?php echo $appItems['sidebar-bottom-core'] ?>
		<div id="pos-profile-sidebar-bottom" class="app-position">
			<?php echo $appItems['sidebar-bottom']; ?>
		</div>
		
		<div class="app-add">
			<a class="app-action-add cFloat-R" href="javascript: void(0)" onclick="joms.editLayout.browse('sidebar-bottom');">
				<i class="com-icon-add"></i>
				<span><?php echo JText::_('COM_COMMUNITY_ADD_APPLICATIONS'); ?></span>
			</a>
		</div>
		
	</div>
	
	<div class="cMain">
	
		<div class="app-item app-core"><?php echo JText::_('COM_COMMUNITY_PROFILE'); ?></div>
		<div class="app-item app-core"><?php echo JText::_('COM_COMMUNITY_ACTIVITY_STREAM'); ?></div>
		<?php echo $appItems['content-core']; ?>

		<div id="pos-profile-content" class="app-position">
			<?php echo $appItems['content']; ?>
		</div>

		<div class="app-add">
			<a class="app-action-add cFloat-R" href="javascript: void(0)" onclick="joms.editLayout.browse('content');">
				<i class="com-icon-add"></i>
				<span><?php echo JText::_('COM_COMMUNITY_ADD_APPLICATIONS'); ?></span>
			</a>
		</div>
		
	</div>
	
</div>