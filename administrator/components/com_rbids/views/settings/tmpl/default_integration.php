<?php defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" method="post" action="index.php?option=<?php echo APP_EXTENSION;?>">
<input type="hidden" name="task" value="changeprofileintegration" />

    <div class='width-100 fltlft'>
    	<fieldset class="adminform">
    		<legend><?php echo JText::_( 'COM_RBIDS_USER_PROFILE_SETTINGS' ); ?></legend> 
    		<table class="paramlist admintable" width="100%">
    			<tr>
    				<td width="20%" class="paramlist_key"><?php echo JText::_("COM_RBIDS_USER_PROFILE");  ?>: </td>
    				<td width="80%">
                        <?php echo $this->profile_select_list;?>
                        <input name="save" type="submit" value="<?php echo JText::_('COM_RBIDS_SAVE');?>"/>
    				</td>
    			</tr>
                <?php if ($this->current_profile_mode<>'component'):?>
    			<tr>
    				<td width="20%" class="paramlist_key"><?php echo JText::_("COM_RBIDS_CONFIGURE_INTEGRATION");  ?>:</td>
    				<td width="80%">
                        <a href="<?php echo $this->configure_link;?>"><?php echo JText::_("COM_RBIDS_SET_UP_FIELD_ASSIGNMENTS");  ?></a>
    				</td>
    			</tr>
                <?php endif; ?> 
    			<tr>
    				<td width="20%"></td>
    				<td width="80%">
    				</td>
    			</tr>
    		</table> 
        </fieldset>
    </div>
</form>
