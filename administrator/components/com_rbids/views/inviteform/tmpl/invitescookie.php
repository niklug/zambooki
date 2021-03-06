<?php

	// Access the file from Joomla environment
	defined('_JEXEC') or die('Restricted access');
?>

<div>
    <table class = "adminlist" style = "margin-left: auto; margin-right: auto; margin-top: 10px;border: 0;border-collapse: collapse;" border = "0">
        <tr style = "border:0;">
		<?php if ($this->lists['users']): ?>
            <td style = "border:0; padding-right: 10px;">
                <span class = "label"><?php echo JText::_('Invite users'); ?>:</span>
                <span style = "display: inline-block; vertical-align: text-top;"><?php echo $this->lists['users']; ?></span>
            </td>
		<?php endif; ?>
		<?php if ($this->lists['groups']): ?>
            <td style = "border:0; padding-left: 10px;">
                <span class = "label"><?php echo JText::_('Invite groups'); ?>:</span>
                <span style = "display: inline-block; vertical-align: text-top;"><?php echo $this->lists['groups']; ?></span>
            </td>
		<?php endif; ?>
        </tr>
    </table>
</div>
<div style = "font-weight: bold; padding-top: 20px;"><?php echo JText::_('COM_RBIDS_INVITE_FORM_HELP'); ?></div>
<div style = "text-align: center; padding-top: 20px;">
	<?php echo $this->lists['buttonInvite'] . ' ' . $this->lists['buttonReset']; ?>
</div>
